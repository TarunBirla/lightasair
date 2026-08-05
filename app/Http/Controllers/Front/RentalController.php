<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\RentalListing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    // ─── Marketplace listing page ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = RentalListing::approved()
                              ->with('category')
                              ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $listings   = $query->paginate(12);
        $categories = \App\Models\Category::where('status', 1)->get();

        return view('front.rentals.index', compact('listings', 'categories'));
    }

    // ─── Single listing detail + booking form ─────────────────────────────────
    public function show(RentalListing $rental)
    {
        abort_unless($rental->isApproved(), 404);

        // Increment view count
        $rental->increment('view_count');

        // Data for the availability calendar JS
        $bookedRanges = $rental->bookedRanges();
        $blockedDates = $rental->blockedDatesList();

        return view('front.rentals.show', compact('rental', 'bookedRanges', 'blockedDates'));
    }

    // ─── Check availability (AJAX) ────────────────────────────────────────────
    public function checkAvailability(Request $request, RentalListing $rental)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'qty'        => 'required|integer|min:1',
        ]);

        $available = $rental->isAvailable($request->start_date, $request->end_date, $request->qty);

        $days     = max(1, Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1);
        $subtotal = $rental->price_per_day * $request->qty * $days;
        $total    = $subtotal + ($rental->deposit_amount ?: 0) + ($request->delivery ? ($rental->delivery_fee ?: 0) : 0);

        return response()->json([
            'available' => $available,
            'days'      => $days,
            'subtotal'  => number_format($subtotal, 2),
            'deposit'   => number_format($rental->deposit_amount ?: 0, 2),
            'total'     => number_format($total, 2),
        ]);
    }

    // ─── Book (create booking) ────────────────────────────────────────────────
    public function book(Request $request, RentalListing $rental)
    {
        abort_unless($rental->isApproved(), 404);

        if (Auth::check() && $rental->user_id === Auth::id()) {
            return back()->withErrors(['start_date' => 'You cannot book your own rental listing.']);
        }

        $validated = $request->validate([
            'start_date'        => 'required|date|after_or_equal:today',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'qty'               => 'required|integer|min:1|max:' . max(1, $rental->total_qty),
            'requires_delivery' => 'nullable',
            'delivery_address'  => 'nullable|string|max:500',
            'customer_notes'    => 'nullable|string|max:1000',
        ]);

        $days = max(1, Carbon::parse($validated['start_date'])
                      ->diffInDays(Carbon::parse($validated['end_date'])) + 1);

        // Validate min / max rental period
        if ($rental->min_rental_days && $days < $rental->min_rental_days) {
            return back()->withErrors(['start_date' => "Minimum rental period is {$rental->min_rental_days} days."]);
        }
        if ($rental->max_rental_days && $days > $rental->max_rental_days) {
            return back()->withErrors(['end_date' => "Maximum rental period is {$rental->max_rental_days} days."]);
        }

        if (! $rental->isAvailable($validated['start_date'], $validated['end_date'], $validated['qty'])) {
            return back()->withErrors(['start_date' => 'Sorry, this equipment is not available for the selected dates.']);
        }

        $delivery    = $request->has('requires_delivery') && $request->requires_delivery == '1';
        $subtotal    = $rental->price_per_day * $validated['qty'] * $days;
        $deliveryFee = $delivery ? ($rental->delivery_fee ?: 0) : 0;
        $deposit     = $rental->deposit_amount ?: 0;
        $total       = $subtotal + $deposit + $deliveryFee;

        RentalBooking::create([
            'booking_ref'       => RentalBooking::generateRef(),
            'rental_listing_id' => $rental->id,
            'customer_id'       => Auth::id(),
            'vendor_id'         => $rental->user_id,
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'total_days'        => $days,
            'qty'               => $validated['qty'],
            'price_per_day'     => $rental->price_per_day,
            'deposit_amount'    => $deposit,
            'delivery_fee'      => $deliveryFee,
            'subtotal'          => $subtotal,
            'total_amount'      => $total,
            'requires_delivery' => $delivery,
            'delivery_address'  => $validated['delivery_address'] ?? null,
            'customer_notes'    => $validated['customer_notes'] ?? null,
            'status'            => 'pending',
            'deposit_status'    => 'pending',
            'payment_status'    => 'unpaid',
        ]);

        return redirect('/my-bookings')
                         ->with('success', 'Booking request sent successfully! The vendor will confirm shortly.');
    }

    // ─── My rental bookings ───────────────────────────────────────────────────
    public function myRentals()
    {
        $bookings = RentalBooking::where('customer_id', Auth::id())
                                  ->with('listing')
                                  ->latest()
                                  ->paginate(10);

        return view('front.rentals.my-rentals', compact('bookings'));
    }

    // ─── Cancel a booking ─────────────────────────────────────────────────────
    public function cancel(Request $request, RentalBooking $booking)
    {
        abort_unless($booking->customer_id === Auth::id(), 403);
        abort_unless($booking->isPending() || $booking->isConfirmed(), 403);

        $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

        $booking->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect('/my-bookings')
                         ->with('success', 'Booking request cancelled.');
    }
}

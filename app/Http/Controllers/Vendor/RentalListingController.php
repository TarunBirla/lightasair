<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RentalBlockedDate;
use App\Models\RentalListing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RentalListingController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $listings = RentalListing::where('user_id', Auth::id())
                                 ->with('category')
                                 ->when($request->status, fn($q) => $q->where('status', $request->status))
                                 ->latest()
                                 ->paginate(12);

        return view('vendor.rentals.index', compact('listings'));
    }

    // ─── Create form ──────────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('vendor.rentals.create', compact('categories'));
    }

    // ─── Store ────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:20',
            'short_description'=> 'nullable|string|max:500',
            'category_id'      => 'nullable|exists:categories,id',
            'condition'        => 'required|in:new,used,refurbished',
            'brand'            => 'nullable|string|max:100',
            'model_number'     => 'nullable|string|max:100',
            'year_manufactured'=> 'nullable|integer|min:1900|max:' . date('Y'),
            'price_per_day'    => 'required|numeric|min:0.01',
            'price_per_week'   => 'nullable|numeric|min:0',
            'deposit_amount'   => 'nullable|numeric|min:0',
            'total_qty'        => 'required|integer|min:1',
            'min_rental_days'  => 'required|integer|min:1',
            'max_rental_days'  => 'nullable|integer|min:1',
            'location'         => 'nullable|string|max:255',
            'offers_delivery'  => 'boolean',
            'delivery_fee'     => 'nullable|numeric|min:0',
            'images'           => 'nullable|array|max:10',
            'images.*'         => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imagePaths[] = $img->store('rentals', 'public');
            }
        }

        $validated['user_id']         = Auth::id();
        $validated['images']          = $imagePaths;
        $validated['available_qty']   = $validated['total_qty'];
        $validated['offers_delivery'] = $request->boolean('offers_delivery');
        $validated['status']          = 'pending';

        RentalListing::create($validated);

        return redirect()->route('vendor.rentals.index')
                         ->with('success', 'Rental listing submitted for admin approval.');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────
    public function show(RentalListing $rental)
    {
        $this->authorizeRental($rental);
        $rental->load(['category', 'rentalBookings.customer', 'blockedDates']);
        return view('vendor.rentals.show', compact('rental'));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────
    public function edit(RentalListing $rental)
    {
        $this->authorizeRental($rental);
        $categories = Category::where('status', 1)->get();
        return view('vendor.rentals.edit', compact('rental', 'categories'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, RentalListing $rental)
    {
        $this->authorizeRental($rental);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:20',
            'short_description'=> 'nullable|string|max:500',
            'category_id'      => 'nullable|exists:categories,id',
            'condition'        => 'required|in:new,used,refurbished',
            'brand'            => 'nullable|string|max:100',
            'model_number'     => 'nullable|string|max:100',
            'year_manufactured'=> 'nullable|integer|min:1900|max:' . date('Y'),
            'price_per_day'    => 'required|numeric|min:0.01',
            'price_per_week'   => 'nullable|numeric|min:0',
            'deposit_amount'   => 'nullable|numeric|min:0',
            'total_qty'        => 'required|integer|min:1',
            'min_rental_days'  => 'required|integer|min:1',
            'max_rental_days'  => 'nullable|integer|min:1',
            'location'         => 'nullable|string|max:255',
            'offers_delivery'  => 'boolean',
            'delivery_fee'     => 'nullable|numeric|min:0',
            'new_images'       => 'nullable|array|max:10',
            'new_images.*'     => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_images'    => 'nullable|array',
        ]);

        $existing = $rental->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $path) {
                Storage::disk('public')->delete($path);
                $existing = array_filter($existing, fn($i) => $i !== $path);
            }
        }
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $img) {
                $existing[] = $img->store('rentals', 'public');
            }
        }

        $validated['images']          = array_values($existing);
        $validated['available_qty']   = $validated['total_qty'];
        $validated['offers_delivery'] = $request->boolean('offers_delivery');
        $validated['status']          = 'pending'; // re-approve on edit

        $rental->update($validated);

        return redirect()->route('vendor.rentals.index')
                         ->with('success', 'Rental listing updated and re-submitted for approval.');
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────
    public function destroy(RentalListing $rental)
    {
        $this->authorizeRental($rental);
        $rental->delete();
        return redirect()->route('vendor.rentals.index')->with('success', 'Listing deleted.');
    }

    // ─── Calendar: view ───────────────────────────────────────────────────────
    public function calendar(RentalListing $rental)
    {
        $this->authorizeRental($rental);

        $bookedRanges  = $rental->bookedRanges();
        $blockedDates  = $rental->blockedDatesList();

        return view('vendor.rentals.calendar', compact('rental', 'bookedRanges', 'blockedDates'));
    }

    // ─── Calendar: block dates (AJAX) ─────────────────────────────────────────
    public function blockDate(Request $request, RentalListing $rental)
    {
        $this->authorizeRental($rental);

        $request->validate([
            'date'   => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        RentalBlockedDate::updateOrCreate(
            ['rental_listing_id' => $rental->id, 'blocked_date' => $request->date],
            ['reason' => $request->reason]
        );

        return response()->json(['status' => true, 'message' => 'Date blocked.']);
    }

    // ─── Calendar: unblock date (AJAX) ───────────────────────────────────────
    public function unblockDate(Request $request, RentalListing $rental)
    {
        $this->authorizeRental($rental);

        $request->validate(['date' => 'required|date']);

        RentalBlockedDate::where('rental_listing_id', $rental->id)
                         ->where('blocked_date', $request->date)
                         ->delete();

        return response()->json(['status' => true, 'message' => 'Date unblocked.']);
    }

    // ─── Vendor booking management ────────────────────────────────────────────
    public function bookings()
    {
        $bookings = \App\Models\RentalBooking::where('vendor_id', Auth::id())
                                              ->with(['listing', 'customer'])
                                              ->latest()
                                              ->paginate(15);

        return view('vendor.rentals.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, \App\Models\RentalBooking $booking)
    {
        abort_unless($booking->vendor_id === Auth::id(), 403);

        $request->validate(['status' => 'required|in:confirmed,active,returned,completed,cancelled']);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    // ─── Guard ────────────────────────────────────────────────────────────────
    private function authorizeRental(RentalListing $rental): void
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

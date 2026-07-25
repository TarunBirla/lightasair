<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\RentalListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    // ─── All rental listings ──────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = RentalListing::with(['vendor', 'category'])->latest();

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('title', 'LIKE', '%' . $request->search . '%');

        $listings = $query->paginate(20);

        $stats = [
            'pending'  => RentalListing::pending()->count(),
            'approved' => RentalListing::approved()->count(),
            'total'    => RentalListing::count(),
        ];

        return view('admin.rentals.index', compact('listings', 'stats'));
    }

    // ─── View single rental listing ───────────────────────────────────────────
    public function show(RentalListing $rental)
    {
        $rental->load(['vendor', 'category', 'rentalBookings.customer']);
        return view('admin.rentals.show', compact('rental'));
    }

    // ─── Approve ──────────────────────────────────────────────────────────────
    public function approve(RentalListing $rental)
    {
        $rental->update([
            'status'           => 'approved',
            'approved_at'      => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Rental listing \"{$rental->title}\" approved.");
    }

    // ─── Reject ───────────────────────────────────────────────────────────────
    public function reject(Request $request, RentalListing $rental)
    {
        $request->validate(['rejection_reason' => 'required|string|min:10|max:1000']);

        $rental->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at'      => null,
        ]);

        return redirect()->back()->with('success', "Rental listing \"{$rental->title}\" rejected.");
    }

    // ─── Toggle featured ──────────────────────────────────────────────────────
    public function toggleFeatured(RentalListing $rental)
    {
        $rental->update(['is_featured' => ! $rental->is_featured]);
        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // ─── Delete ───────────────────────────────────────────────────────────────
    public function destroy(RentalListing $rental)
    {
        $rental->forceDelete();
        return redirect()->route('admin.rentals.index')->with('success', 'Rental listing deleted.');
    }

    // ─── All rental bookings ──────────────────────────────────────────────────
    public function bookings(Request $request)
    {
        $bookings = RentalBooking::with(['listing', 'customer', 'vendor'])
                                  ->when($request->status, fn($q) => $q->where('status', $request->status))
                                  ->latest()
                                  ->paginate(20);

        return view('admin.rentals.bookings', compact('bookings'));
    }
}

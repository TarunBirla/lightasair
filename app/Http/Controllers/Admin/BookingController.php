<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\RequestLead;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(
            'user'
        )
            ->latest()
            ->get();

        return view(
            'admin.bookings.index',
            compact('bookings')
        );
    }

    public function users()
    {
        $users = User::where(
            'role',
            'user'
        )
            ->latest()
            ->get();

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function requests()
{
    $requests =
    RequestLead::latest()->paginate(20);

    return view(
        'admin.requests.index',
        compact('requests')
    );
}

public function deleteRequest($id)
{
    $request = RequestLead::findOrFail($id);

    $request->delete();

    return redirect()
        ->back()
        ->with(
            'success',
            'Request deleted successfully.'
        );
}
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'items.item'
        ])->findOrFail($id);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }
    public function approve($id)
    {
        Booking::findOrFail($id)
            ->update([
                'status' => 'approved'
            ]);

        return back();
    }

    public function reject($id)
    {
        Booking::findOrFail($id)
            ->update([
                'status' => 'rejected'
            ]);

        return back();
    }

    public function complete($id)
    {
        Booking::findOrFail($id)
            ->update([
                'status' => 'completed'
            ]);

        return back();
    }
}
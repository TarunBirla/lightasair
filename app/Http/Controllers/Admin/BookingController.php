<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;

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
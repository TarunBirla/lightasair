<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RentalBooking;
use App\Models\AuctionBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // List all invoices for the logged-in customer/vendor/admin
    public function index()
    {
        $user = Auth::user();

        // Customer's orders & bookings
        $orders = Order::where('customer_id', $user->id)
            ->with(['items.product', 'vendor'])
            ->latest()
            ->get();

        $bookings = RentalBooking::where('customer_id', $user->id)
            ->with(['listing', 'vendor'])
            ->latest()
            ->get();

        return view('front.account.invoices', compact('orders', 'bookings'));
    }

    // View / Download Order Invoice
    public function orderInvoice(Order $order)
    {
        $user = Auth::user();

        // Check permissions: Customer, Vendor, or Admin
        if ($order->customer_id !== $user->id && $order->vendor_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $order->load(['customer', 'vendor', 'items.product']);

        return view('front.invoices.order', compact('order'));
    }

    // View / Download Rental Booking Invoice
    public function bookingInvoice(RentalBooking $booking)
    {
        $user = Auth::user();

        // Check permissions: Customer, Vendor, or Admin
        if ($booking->customer_id !== $user->id && $booking->vendor_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $booking->load(['customer', 'vendor', 'listing']);

        return view('front.invoices.booking', compact('booking'));
    }

    // View User Bids
    public function myBids()
    {
        $bids = AuctionBid::where('user_id', Auth::id())
            ->with(['auction.vendor', 'auction.category'])
            ->latest()
            ->paginate(15);

        return view('front.account.bids', compact('bids'));
    }
}

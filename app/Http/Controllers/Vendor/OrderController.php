<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('vendor_id', auth()->id());

        if ($request->filled('fulfillment_status')) {
            $query->where('fulfillment_status', $request->fulfillment_status);
        }

        $orders = $query->paginate(15);
        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->vendor_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items', 'customer']);
        return view('vendor.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->vendor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'fulfillment_status' => 'required|in:processing,shipped,delivered',
        ]);

        $order->update([
            'fulfillment_status' => $request->fulfillment_status,
        ]);

        return back()->with('success', 'Order status updated successfully');
    }
}

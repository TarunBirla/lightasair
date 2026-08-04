<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'vendor']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('fulfillment_status')) {
            $query->where('fulfillment_status', $request->fulfillment_status);
        }

        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        $orders = $query->paginate(20);

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('fulfillment_status', 'pending')->count(),
            'delivered_orders' => Order::where('fulfillment_status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('subtotal'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'customer', 'vendor', 'payout']);
        return view('admin.orders.show', compact('order'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,refunded',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Payment status updated successfully');
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Product $product)
    {
        if (!$product->isApproved() || $product->listing_type !== 'sell') {
            abort(404);
        }

        return view('front.orders.checkout', compact('product'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isApproved() || $product->listing_type !== 'sell') {
            abort(404);
        }

        $subtotal = $product->price * $request->qty;
        
        $rate = Commission::getRate($product->category_id, 'sell');
        $commissionAmount = $subtotal * ($rate / 100);
        $vendorPayout = $subtotal - $commissionAmount;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_id' => auth()->id(),
            'vendor_id' => $product->user_id,
            'type' => 'sell',
            'subtotal' => $subtotal,
            'commission_rate' => $rate,
            'commission_amount' => $commissionAmount,
            'vendor_payout' => $vendorPayout,
            'payment_status' => 'pending',
            'fulfillment_status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'item_name' => $product->title,
            'item_type' => 'sell',
            'qty' => $request->qty,
            'unit_price' => $product->price,
            'line_total' => $subtotal,
        ]);

        Payout::create([
            'vendor_id' => $product->user_id,
            'order_id' => $order->id,
            'amount' => $vendorPayout,
            'status' => 'pending',
        ]);

        return redirect()->route('front.my-orders')->with('success', 'Order placed successfully');
    }

    public function myOrders()
    {
        $orders = Order::where('customer_id', auth()->id())->paginate(10);
        return view('front.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');
        return view('front.orders.show', compact('order'));
    }
}

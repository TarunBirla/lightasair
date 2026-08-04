@extends('front.layouts.app')
@section('title', 'Order #'.$order->order_number)
@section('content')
<style>
.order-section { padding: 3rem 0 5rem; background: #F5F4EF; min-height: 80vh; }
.order-breadcrumb { background: #f5f4ef; padding: 1rem 0; border-bottom: 1px solid #e8e6de; font-size: .85rem; color: #888; }
.order-breadcrumb a { color: #888; text-decoration: none; }
.order-breadcrumb a:hover { color: var(--brand); }
.order-breadcrumb span { margin: 0 .5rem; }
.order-grid { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }
@media(max-width: 960px) { .order-grid { grid-template-columns: 1fr; } }
.order-card { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 2rem; margin-bottom: 2rem; }
.card-title { font-size: 1.2rem; font-weight: 800; color: #111; margin-bottom: 1.2rem; padding-bottom: .8rem; border-bottom: 1px solid #eee; }
.items-table { width: 100%; border-collapse: collapse; }
.items-table th { text-align: left; padding: 1rem .5rem; font-size: .75rem; text-transform: uppercase; color: #888; border-bottom: 1px solid #eee; }
.items-table td { padding: 1rem .5rem; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
.info-row { display: flex; justify-content: space-between; margin-bottom: .8rem; font-size: .95rem; }
</style>

<div class="order-breadcrumb">
    <div class="container">
        <a href="/">Home</a><span>/</span>
        <a href="{{ route('front.orders.index') }}">My Orders</a><span>/</span>
        #{{ $order->order_number }}
    </div>
</div>

<div class="order-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-weight:900;font-size:2rem;margin:0;">Order #{{ $order->order_number }}</h1>
                <div style="color:#666;font-size:.9rem;margin-top:.3rem;">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</div>
            </div>
            <div class="d-flex gap-2">
                @if($order->payment_status == 'paid')
                    <span class="badge bg-success fs-6 px-3 py-2">Paid</span>
                @else
                    <span class="badge bg-secondary fs-6 px-3 py-2">Unpaid</span>
                @endif
                
                @if($order->fulfillment_status == 'delivered')
                    <span class="badge bg-success fs-6 px-3 py-2">Delivered</span>
                @elseif($order->fulfillment_status == 'shipped')
                    <span class="badge bg-primary fs-6 px-3 py-2">Shipped</span>
                @elseif($order->fulfillment_status == 'processing')
                    <span class="badge bg-info text-dark fs-6 px-3 py-2">Processing</span>
                @elseif($order->fulfillment_status == 'cancelled')
                    <span class="badge bg-danger fs-6 px-3 py-2">Cancelled</span>
                @else
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">Pending</span>
                @endif
            </div>
        </div>

        <div class="order-grid">
            <div>
                <div class="order-card">
                    <h2 class="card-title">Order Items</h2>
                    <div style="overflow-x:auto;">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th style="text-align:right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div style="font-weight:700;">{{ $item->product_name }}</div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>£{{ number_format($item->unit_price, 2) }}</td>
                                    <td style="text-align:right;font-weight:700;">£{{ number_format($item->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="order-card">
                    <h2 class="card-title">Vendor Information</h2>
                    <p style="margin:0;font-weight:600;">{{ $order->vendor->name ?? 'N/A' }}</p>
                    <p style="margin:0;color:#666;font-size:.9rem;">{{ $order->vendor->email ?? 'N/A' }}</p>
                </div>
            </div>

            <div>
                <div class="order-card">
                    <h2 class="card-title">Order Summary</h2>
                    <div class="info-row">
                        <span class="text-muted">Subtotal</span>
                        <span style="font-weight:600;">£{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="info-row" style="border-top:1px solid #eee;padding-top:1rem;margin-top:1rem;font-size:1.2rem;font-weight:800;">
                        <span>Total</span>
                        <span style="color:var(--brand-dk);">£{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                </div>

                <div class="order-card">
                    <h2 class="card-title">Shipping Address</h2>
                    <div style="white-space:pre-line;color:#555;font-size:.95rem;line-height:1.6;">{{ $order->shipping_address }}</div>
                    
                    @if($order->notes)
                        <h2 class="card-title" style="margin-top:1.5rem;font-size:1rem;">Order Notes</h2>
                        <div style="color:#555;font-size:.95rem;line-height:1.6;">{{ $order->notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

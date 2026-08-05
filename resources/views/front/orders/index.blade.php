@extends('front.account.layout')

@section('title', 'My Marketplace Purchases — Light As Air')

@section('account_content')

<style>
.card-table { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.04); }
.orders-table { width: 100%; border-collapse: collapse; text-align: left; }
.orders-table th { background: #fafaf8; font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #888; padding: 1rem 1.2rem; border-bottom: 1px solid #e5e4df; }
.orders-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #f0ede8; font-size: .9rem; vertical-align: middle; }
.orders-table tr:last-child td { border: none; }
.orders-table tr:hover td { background: #fefefc; }
.btn-inv { background: #111; color: var(--brand, #FFC700); font-weight: 700; font-size: .78rem; padding: .35rem .85rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: .3rem; }
.btn-inv:hover { background: #333; color: var(--brand, #FFC700); }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.6rem;font-weight:900;margin:0;color:#111;">Marketplace Purchases</h2>
        <p style="color:#888;font-size:.88rem;margin:0;">Track your orders, delivery status, and view invoices</p>
    </div>
    <a href="/marketplace" class="btn btn-dark" style="border-radius:10px;font-weight:700;">
        <i class="bi bi-shop me-1"></i> Browse Marketplace
    </a>
</div>

<div class="card-table">
    @if($orders->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;color:#888;">
            <i class="bi bi-bag-x" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
            <h4 style="font-weight:800;color:#111;">No orders placed yet</h4>
            <p>Looks like you haven't bought any items from the marketplace yet.</p>
            <a href="/marketplace" class="btn btn-warning mt-2" style="font-weight:800;border-radius:10px;">Explore Marketplace</a>
        </div>
    @else
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Seller</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Fulfillment</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('front.orders.show', $order->id) }}" style="font-weight:800;color:#111;text-decoration:none;">
                            #{{ $order->order_number }}
                        </a>
                    </td>
                    <td style="font-size:.85rem;color:#666;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td style="font-weight:600;">{{ $order->vendor->name ?? 'Vendor' }}</td>
                    <td style="font-weight:900;color:#16a34a;font-size:1rem;">£{{ number_format($order->subtotal, 2) }}</td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success" style="font-size:.7rem;">PAID</span>
                        @else
                            <span class="badge bg-warning text-dark" style="font-size:.7rem;">UNPAID</span>
                        @endif
                    </td>
                    <td>
                        @if($order->fulfillment_status == 'delivered')
                            <span class="badge bg-success" style="font-size:.7rem;">Delivered</span>
                        @elseif($order->fulfillment_status == 'shipped')
                            <span class="badge bg-primary" style="font-size:.7rem;">Shipped</span>
                        @elseif($order->fulfillment_status == 'processing')
                            <span class="badge bg-info text-dark" style="font-size:.7rem;">Processing</span>
                        @elseif($order->fulfillment_status == 'cancelled')
                            <span class="badge bg-danger" style="font-size:.7rem;">Cancelled</span>
                        @else
                            <span class="badge bg-secondary" style="font-size:.7rem;">Pending</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('front.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-size:.78rem;font-weight:700;">
                                Details
                            </a>
                            <a href="/invoices/order/{{ $order->id }}" target="_blank" class="btn-inv" title="Print Invoice">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $orders->links() }}
</div>

@endsection

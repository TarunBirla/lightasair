@extends('front.layouts.app')
@section('title', 'My Orders')
@section('content')
<style>
.orders-hero { background: #111; padding: 4rem 0; color: #fff; text-align: center; }
.orders-hero h1 { font-weight: 900; font-size: 2.5rem; margin-bottom: .5rem; }
.orders-hero p { color: #aaa; font-size: 1.1rem; }
.orders-section { padding: 3rem 0 5rem; background: #F5F4EF; min-height: 60vh; }
.order-card { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem; transition: transform .2s; }
.order-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
.order-number { font-size: 1.25rem; font-weight: 800; color: #111; text-decoration: none; }
.order-date { font-size: .85rem; color: #888; }
.order-body { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
.order-total { font-size: 1.25rem; font-weight: 800; color: #16a34a; }
.badges { display: flex; gap: .5rem; flex-wrap: wrap; }
@media(min-width: 768px) {
    .order-card { flex-direction: row; align-items: center; justify-content: space-between; }
    .order-header { border: none; padding: 0; flex-direction: column; align-items: flex-start; flex: 1; }
    .order-body { flex: 2; justify-content: flex-end; gap: 2rem; }
}
</style>

<div class="orders-hero">
    <div class="container">
        <h1>My Orders</h1>
        <p>Track and manage your purchases</p>
    </div>
</div>

<div class="orders-section">
    <div class="container">
        @if($orders->isEmpty())
            <div style="text-align:center;padding:4rem 1rem;color:#888;">
                <i class="bi bi-bag-x" style="font-size:4rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
                <h3 style="font-weight:800;color:#111;">No orders yet</h3>
                <p>Looks like you haven't placed any orders.</p>
                <a href="/marketplace" class="btn-brand mt-3">Browse Marketplace</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <a href="{{ route('front.orders.show', $order->id) }}" class="order-number">#{{ $order->order_number }}</a>
                            <div class="order-date">{{ $order->created_at->format('M d, Y - H:i') }}</div>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="badges">
                            <span class="badge bg-dark">{{ ucfirst($order->type ?? 'Order') }}</span>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-secondary">Unpaid</span>
                            @endif
                            
                            @if($order->fulfillment_status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->fulfillment_status == 'shipped')
                                <span class="badge bg-primary">Shipped</span>
                            @elseif($order->fulfillment_status == 'processing')
                                <span class="badge bg-info text-dark">Processing</span>
                            @elseif($order->fulfillment_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                        <div class="order-total">£{{ number_format($order->subtotal, 2) }}</div>
                        <a href="{{ route('front.orders.show', $order->id) }}" class="btn-brand-outline" style="font-size:.85rem;padding:.4rem 1rem;">View Details</a>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

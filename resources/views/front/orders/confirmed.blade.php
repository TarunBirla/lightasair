@extends('front.layouts.app')
@section('title', 'Order Confirmed')
@section('content')
<style>
.confirmed-section { padding: 4rem 0 6rem; background: #F5F4EF; min-height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; }
.confirmed-card { background: #fff; border-radius: 20px; padding: 4rem 3rem; max-width: 600px; width: 100%; box-shadow: var(--shadow-lg); border: 1px solid #e5e4df; }
.success-icon { font-size: 5rem; color: #16a34a; margin-bottom: 1.5rem; line-height: 1; }
.order-num { font-size: 2rem; font-weight: 900; margin-bottom: 1rem; color: #111; }
.order-msg { font-size: 1.1rem; color: #666; margin-bottom: 2rem; }
.summary-box { background: #fafafa; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #eee; text-align: left; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: .75rem; font-size: .95rem; }
.summary-row:last-child { margin-bottom: 0; border-top: 1px solid #ddd; padding-top: .75rem; font-weight: 800; font-size: 1.1rem; }
</style>

<div class="confirmed-section">
    <div class="container">
        <div class="confirmed-card mx-auto">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h1 style="font-weight:900;">Order Confirmed!</h1>
            <p class="order-msg">Thank you for your purchase. Your order has been placed successfully.</p>
            
            <div class="order-num">#{{ $order->order_number }}</div>

            <div class="summary-box">
                <div class="summary-row">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-warning text-dark">Pending</span>
                </div>
                <div class="summary-row">
                    <span class="text-muted">Payment</span>
                    <span class="badge bg-secondary">Unpaid</span>
                </div>
                <div class="summary-row">
                    <span>Total Amount</span>
                    <span style="color:var(--brand-dk);">£{{ number_format($order->subtotal, 2) }}</span>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-center">
                <a href="/my-orders" class="btn-brand">View My Orders</a>
                <a href="/marketplace" class="btn-brand-outline">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection

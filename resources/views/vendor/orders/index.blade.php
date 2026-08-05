@extends('layouts.vendor')
@section('title', 'Orders')
@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">Customer Orders</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Manage and fulfill your marketplace orders</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

<div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
    <div class="mb-4 d-flex" style="gap:.5rem;">
        <form method="GET" action="{{ route('vendor.orders.index') }}" class="d-flex w-100" style="gap:.5rem;max-width:500px;">
            <select name="fulfillment_status" class="form-select" style="border-radius:10px;font-size:.9rem;">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('fulfillment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('fulfillment_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('fulfillment_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('fulfillment_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('fulfillment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-brand" style="border-radius:10px;">Filter</button>
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-light" style="border-radius:10px;border:1px solid #ddd;">Reset</a>
        </form>
    </div>

    @if($orders->isEmpty())
        <div style="text-align:center;padding:3rem 1rem;color:#888;">
            <i class="bi bi-box-seam" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
            <h4>No orders found</h4>
            <p>When customers purchase your items, they will appear here.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle vendor-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Commission</th>
                        <th>Your Payout</th>
                        <th>Fulfillment</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td style="font-weight:700;"><a href="{{ route('vendor.orders.show', $order->id) }}" style="color:#111;text-decoration:none;">{{ $order->order_number }}</a></td>
                        <td>{{ $order->customer->name ?? 'Guest' }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td style="font-weight:700;">£{{ number_format($order->subtotal, 2) }}</td>
                        <td style="color:#dc2626;font-size:.85rem;">-£{{ number_format($order->commission_amount, 2) }}</td>
                        <td style="color:#16a34a;font-weight:800;">£{{ number_format($order->vendor_payout_amount, 2) }}</td>
                        <td>
                            @if($order->fulfillment_status == 'delivered')
                                <span class="status-badge bg-success text-white">Delivered</span>
                            @elseif($order->fulfillment_status == 'shipped')
                                <span class="status-badge bg-primary text-white">Shipped</span>
                            @elseif($order->fulfillment_status == 'processing')
                                <span class="status-badge bg-info text-dark">Processing</span>
                            @elseif($order->fulfillment_status == 'cancelled')
                                <span class="status-badge bg-danger text-white">Cancelled</span>
                            @else
                                <span class="status-badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="status-badge bg-success text-white">Paid</span>
                            @else
                                <span class="status-badge bg-secondary text-white">Unpaid</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;color:#888;">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('vendor.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;">View</a>
                            <a href="/invoices/order/{{ $order->id }}" target="_blank" class="btn btn-sm btn-dark" style="border-radius:8px;font-weight:700;" title="Invoice">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

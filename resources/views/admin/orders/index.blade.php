@extends('layouts.admin')
@section('page-title', 'Orders Management')
@section('breadcrumb', 'Admin / Orders')
@section('content')
<style>
.stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 1.8rem; }
.stat-box { background: #fff; border-radius: 12px; padding: 1.2rem 1.4rem; border: 1px solid #E8E6DF; display: flex; align-items: center; gap: 1rem; }
.stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.stat-icon.yellow { background: #FFF3B0; color: #B38A00; }
.stat-icon.green  { background: #EDFAF0; color: #1a7a3a; }
.stat-icon.purple { background: #FCE7F3; color: #BE185D; }
.stat-icon.blue   { background: #EAF3FF; color: #1a5fb4; }
.stat-num   { font-size: 1.7rem; font-weight: 800; color: #111; line-height: 1; }
.stat-label { font-size: .75rem; color: #888; margin-top: .15rem; }

.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.4rem; align-items: center; }
.toolbar form { display: flex; gap: .6rem; flex-wrap: wrap; }
.f-input, .f-select { padding: .5rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .85rem; background: #fff; outline: none; font-family: inherit; }
.f-input:focus, .f-select:focus { border-color: #FFC700; }
.btn-go  { padding: .5rem 1.1rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; }
.btn-clr { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; text-decoration: none; color: #555; }

.admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.admin-table th { background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF; }
.admin-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border: none; }
.admin-table tr:hover td { background: #fefef9; }
.ab-view { background: #f3f4f6; color: #374151; padding: .3rem .65rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; }
</style>

<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-receipt"></i></div>
        <div><div class="stat-num">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Orders</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-hourglass-half"></i></div>
        <div><div class="stat-num">{{ $stats['pending'] ?? 0 }}</div><div class="stat-label">Pending Fulfillment</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-truck-fast"></i></div>
        <div><div class="stat-num">{{ $stats['delivered'] ?? 0 }}</div><div class="stat-label">Delivered Orders</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon purple"><i class="fa-solid fa-pound-sign"></i></div>
        <div><div class="stat-num">£{{ number_format($stats['revenue'] ?? 0, 2) }}</div><div class="stat-label">Total Revenue</div></div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <input type="text" name="search" class="f-input" placeholder="Search order #…" value="{{ request('search') }}">
        <select name="type" class="f-select">
            <option value="">All Types</option>
            <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Sell</option>
            <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Rent</option>
            <option value="auction" {{ request('type') == 'auction' ? 'selected' : '' }}>Auction</option>
        </select>
        <select name="payment_status" class="f-select">
            <option value="">Payment Status</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        </select>
        <select name="fulfillment_status" class="f-select">
            <option value="">Fulfillment Status</option>
            <option value="pending" {{ request('fulfillment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('fulfillment_status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('fulfillment_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('fulfillment_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('fulfillment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-clr">Reset</a>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Vendor</th>
            <th>Type</th>
            <th>Subtotal</th>
            <th>Commission</th>
            <th>Payment</th>
            <th>Fulfillment</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td style="font-weight:700;"><a href="{{ route('admin.orders.show', $order->id) }}" style="color:#111;text-decoration:none;">{{ $order->order_number }}</a></td>
            <td>{{ $order->customer->name ?? 'Guest' }}</td>
            <td>{{ $order->vendor->name ?? 'N/A' }}</td>
            <td><span class="badge bg-dark">{{ ucfirst($order->type ?? 'Order') }}</span></td>
            <td style="font-weight:700;">£{{ number_format($order->subtotal, 2) }}</td>
            <td style="color:#dc2626;font-size:.85rem;">£{{ number_format($order->commission_amount, 2) }}</td>
            <td>
                @if($order->payment_status == 'paid')
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-secondary">Unpaid</span>
                @endif
            </td>
            <td>
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
            </td>
            <td style="font-size:.8rem;color:#888;">{{ $order->created_at->format('d M Y') }}</td>
            <td>
                <div style="display:flex;gap:.3rem;">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="ab-view"><i class="fa-solid fa-eye me-1"></i> View</a>
                    <a href="/invoices/order/{{ $order->id }}" target="_blank" class="ab-view" style="background:#111;color:#FFC700;"><i class="fa-solid fa-file-invoice me-1"></i> Invoice</a>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center p-4 text-muted">No orders found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
@endsection

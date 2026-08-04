@extends('layouts.vendor')
@section('title', 'Order #'.$order->order_number)
@section('content')
<div class="mb-4">
    <a href="{{ route('vendor.orders.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;border:1px solid #ddd;font-weight:600;">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="content-card-title m-0">Order Items</h5>
                <div class="text-muted" style="font-size:.85rem;">{{ $order->created_at->format('M d, Y h:i A') }}</div>
            </div>
            <div class="content-card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle vendor-table m-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>£{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end" style="font-weight:700;">£{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="content-card-title m-0">Customer Info</h5>
                    </div>
                    <div class="content-card-body">
                        <div style="font-weight:700;">{{ $order->customer->name ?? 'Guest' }}</div>
                        <div style="color:#666;font-size:.9rem;margin-bottom:1rem;">{{ $order->customer->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-card">
                    <div class="content-card-header">
                        <h5 class="content-card-title m-0">Shipping Details</h5>
                    </div>
                    <div class="content-card-body">
                        <div style="white-space:pre-line;color:#444;font-size:.9rem;margin-bottom:1rem;">{{ $order->shipping_address }}</div>
                        @if($order->notes)
                            <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;color:#888;margin-bottom:.3rem;">Notes:</div>
                            <div style="color:#555;font-size:.9rem;font-style:italic;">{{ $order->notes }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5 class="content-card-title m-0">Order Summary</h5>
            </div>
            <div class="content-card-body">
                <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                    <span class="text-muted">Subtotal</span>
                    <span style="font-weight:600;">£{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                    <span class="text-muted">Commission ({{ $order->commission_rate }}%)</span>
                    <span style="color:#dc2626;">-£{{ number_format($order->commission_amount, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:1.1rem;font-weight:800;">
                    <span>Your Payout</span>
                    <span style="color:#16a34a;">£{{ number_format($order->vendor_payout_amount, 2) }}</span>
                </div>
                
                <div class="mt-4 pt-4 border-top">
                    <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;color:#888;margin-bottom:.5rem;">Payment Status</div>
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success fs-6 px-3 py-2 w-100">Paid</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2 w-100">Unpaid</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title m-0">Update Fulfillment</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('vendor.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="fulfillment_status" class="form-select" style="border-radius:8px;">
                            <option value="pending" {{ $order->fulfillment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->fulfillment_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->fulfillment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->fulfillment_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->fulfillment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-brand w-100">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

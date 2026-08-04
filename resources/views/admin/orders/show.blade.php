@extends('layouts.admin')
@section('page-title', 'Order #'.$order->order_number)
@section('breadcrumb', 'Admin / Orders / '.$order->order_number)
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;border:1px solid #ddd;font-weight:600;">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4" style="border-radius:12px;border:1px solid #E8E6DF;box-shadow:none;">
            <div class="card-header bg-white p-3" style="border-bottom:1px solid #E8E6DF;">
                <h5 class="m-0" style="font-weight:800;font-size:1.1rem;">Order Items</h5>
            </div>
            <div class="card-body p-0">
                <table class="table m-0 align-middle">
                    <thead style="background:#f9f8f4;font-size:.75rem;text-transform:uppercase;color:#888;">
                        <tr>
                            <th class="ps-4 py-3 border-0">Item</th>
                            <th class="border-0">Qty</th>
                            <th class="border-0">Unit Price</th>
                            <th class="pe-4 text-end border-0">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="ps-4 py-3" style="font-weight:600;">{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>£{{ number_format($item->unit_price, 2) }}</td>
                            <td class="pe-4 text-end" style="font-weight:700;">£{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4" style="border-radius:12px;border:1px solid #E8E6DF;box-shadow:none;">
                    <div class="card-body p-4">
                        <h6 style="font-weight:800;text-transform:uppercase;color:#888;font-size:.8rem;margin-bottom:1rem;">Customer Information</h6>
                        <div style="font-weight:700;font-size:1.05rem;">{{ $order->customer->name ?? 'Guest' }}</div>
                        <div style="color:#666;font-size:.9rem;margin-bottom:1.5rem;">{{ $order->customer->email ?? 'N/A' }}</div>

                        <h6 style="font-weight:800;text-transform:uppercase;color:#888;font-size:.8rem;margin-bottom:1rem;">Vendor Information</h6>
                        <div style="font-weight:700;font-size:1.05rem;">{{ $order->vendor->name ?? 'N/A' }}</div>
                        <div style="color:#666;font-size:.9rem;">{{ $order->vendor->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4" style="border-radius:12px;border:1px solid #E8E6DF;box-shadow:none;">
                    <div class="card-body p-4">
                        <h6 style="font-weight:800;text-transform:uppercase;color:#888;font-size:.8rem;margin-bottom:1rem;">Shipping Details</h6>
                        <div style="white-space:pre-line;color:#444;font-size:.95rem;line-height:1.6;margin-bottom:1rem;">{{ $order->shipping_address }}</div>
                        
                        <div class="mt-3 pt-3" style="border-top:1px solid #eee;">
                            <h6 style="font-weight:800;text-transform:uppercase;color:#888;font-size:.8rem;margin-bottom:.5rem;">Fulfillment Status</h6>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4" style="border-radius:12px;border:1px solid #E8E6DF;box-shadow:none;">
            <div class="card-header bg-white p-3" style="border-bottom:1px solid #E8E6DF;">
                <h5 class="m-0" style="font-weight:800;font-size:1.1rem;">Financials</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2" style="font-size:.95rem;">
                    <span class="text-muted">Subtotal</span>
                    <span style="font-weight:600;">£{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:.95rem;">
                    <span class="text-muted">Platform Commission ({{ $order->commission_rate }}%)</span>
                    <span style="color:#16a34a;font-weight:700;">+£{{ number_format($order->commission_amount, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:1.1rem;font-weight:800;">
                    <span>Vendor Payout</span>
                    <span style="color:#dc2626;">£{{ number_format($order->vendor_payout_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="card mb-4" style="border-radius:12px;border:1px solid #E8E6DF;box-shadow:none;">
            <div class="card-header bg-white p-3" style="border-bottom:1px solid #E8E6DF;">
                <h5 class="m-0" style="font-weight:800;font-size:1.1rem;">Update Payment</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;color:#888;margin-bottom:.5rem;">Current Status</div>
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success px-3 py-2 fs-6">Paid</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 fs-6">Unpaid</span>
                    @endif
                </div>

                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST" class="mt-4 pt-3 border-top">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="payment_status" class="form-select" style="border-radius:8px;">
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100" style="border-radius:8px;font-weight:700;background:var(--brand);color:#111;border:none;">Update Payment Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

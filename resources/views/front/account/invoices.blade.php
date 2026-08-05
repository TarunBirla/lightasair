@extends('front.account.layout')

@section('title', 'My Invoices & Receipts — Light As Air')

@section('account_content')

<style>
.card-table { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.04); }
.inv-table { width: 100%; border-collapse: collapse; text-align: left; }
.inv-table th { background: #fafaf8; font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #888; padding: 1rem 1.2rem; border-bottom: 1px solid #e5e4df; }
.inv-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #f0ede8; font-size: .9rem; vertical-align: middle; }
.inv-table tr:last-child td { border: none; }
.inv-table tr:hover td { background: #fefefc; }
.btn-inv { background: #111; color: var(--brand, #FFC700); font-weight: 700; font-size: .8rem; padding: .4rem .9rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: .35rem; transition: all .2s; }
.btn-inv:hover { background: #333; color: var(--brand, #FFC700); }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.6rem;font-weight:900;margin:0;color:#111;">Invoices & Receipts</h2>
        <p style="color:#888;font-size:.88rem;margin:0;">View and download official invoices for your marketplace purchases & equipment rentals</p>
    </div>
</div>

<div class="card-table">
    @if($orders->isEmpty() && $bookings->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;color:#888;">
            <i class="bi bi-file-earmark-x" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
            <h4 style="font-weight:800;color:#111;">No invoices found</h4>
            <p>Once you make a purchase or complete a rental booking, your official invoices will appear here.</p>
        </div>
    @else
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Invoice / Ref #</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Vendor / Seller</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th style="width:140px;">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- Orders Invoices --}}
                @foreach($orders as $order)
                <tr>
                    <td>
                        <strong style="color:#111;">#{{ $order->order_number }}</strong>
                    </td>
                    <td><span class="badge bg-primary" style="font-size:.7rem;">Marketplace Purchase</span></td>
                    <td style="font-size:.85rem;color:#666;">{{ $order->created_at->format('d M Y') }}</td>
                    <td style="font-weight:600;">{{ $order->vendor->name ?? 'Vendor' }}</td>
                    <td style="font-weight:800;color:#16a34a;">£{{ number_format($order->subtotal, 2) }}</td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success" style="font-size:.7rem;">PAID</span>
                        @else
                            <span class="badge bg-warning text-dark" style="font-size:.7rem;">UNPAID</span>
                        @endif
                    </td>
                    <td>
                        <a href="/invoices/order/{{ $order->id }}" target="_blank" class="btn-inv">
                            <i class="bi bi-printer"></i> Invoice
                        </a>
                    </td>
                </tr>
                @endforeach

                {{-- Rental Booking Invoices --}}
                @foreach($bookings as $booking)
                <tr>
                    <td>
                        <strong style="color:#16a34a;font-family:monospace;">{{ $booking->booking_ref }}</strong>
                    </td>
                    <td><span class="badge bg-success" style="font-size:.7rem;">Equipment Hire</span></td>
                    <td style="font-size:.85rem;color:#666;">{{ $booking->created_at->format('d M Y') }}</td>
                    <td style="font-weight:600;">{{ $booking->vendor->name ?? 'Vendor' }}</td>
                    <td style="font-weight:800;color:#16a34a;">£{{ number_format($booking->total_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-success" style="font-size:.7rem;">{{ strtoupper($booking->status) }}</span>
                    </td>
                    <td>
                        <a href="/invoices/booking/{{ $booking->id }}" target="_blank" class="btn-inv">
                            <i class="bi bi-printer"></i> Invoice
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection

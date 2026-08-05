@extends('layouts.vendor')

@section('title', 'Incoming Rental Bookings')

@section('content')

<style>
.card-table { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.04); }
.bookings-table { width: 100%; border-collapse: collapse; text-align: left; }
.bookings-table th { background: #fafaf8; font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #888; padding: 1rem 1.2rem; border-bottom: 1px solid #e5e4df; }
.bookings-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #f0ede8; font-size: .9rem; vertical-align: middle; }
.bookings-table tr:last-child td { border: none; }
.bookings-table tr:hover td { background: #fefefc; }

.status-badge { padding: .3rem .75rem; border-radius: 20px; font-size: .72rem; font-weight: 800; text-transform: uppercase; }
.badge-pending   { background: #fff3cd; color: #856404; }
.badge-confirmed { background: #dcfce7; color: #166534; }
.badge-active    { background: #dbeafe; color: #1e40af; }
.badge-returned  { background: #f3e8ff; color: #6b21a8; }
.badge-completed { background: #f3f4f6; color: #374151; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }

.btn-inv { background: #111; color: var(--brand, #FFC700); font-weight: 700; font-size: .78rem; padding: .35rem .85rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: .3rem; transition: all .2s; }
.btn-inv:hover { background: #333; color: var(--brand, #FFC700); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 style="font-size:1.6rem;font-weight:900;margin:0;color:#111;">
            <i class="bi bi-calendar-event-fill text-warning me-2"></i> Incoming Rental Bookings
        </h2>
        <p style="color:#888;font-size:.88rem;margin:0;">Track and manage equipment hire requests submitted for your listings</p>
    </div>
    <a href="{{ route('vendor.rentals.index') }}" class="btn btn-outline-dark" style="border-radius:10px;font-weight:700;font-size:.85rem;">
        <i class="bi bi-calendar3 me-1"></i> Manage Rental Listings
    </a>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

<div class="card-table">
    @if($bookings->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;color:#888;">
            <i class="bi bi-calendar-x" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
            <h4 style="font-weight:800;color:#111;">No rental bookings received yet</h4>
            <p>When customers request equipment hire for your listings, their bookings will appear here.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Equipment</th>
                        <th>Customer</th>
                        <th>Rental Period</th>
                        <th>Total Paid</th>
                        <th>Current Status</th>
                        <th style="width:230px;">Update Status & Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td style="font-family:monospace;font-weight:800;color:#16a34a;">
                            {{ $booking->booking_ref }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $booking->listing?->primaryImageUrl() }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid #eee;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' fill=\'%23eee\'><text x=\'20\' y=\'24\' font-family=\'sans-serif\' font-size=\'10\' fill=\'%23888\' text-anchor=\'middle\'>Img</text></svg>'">
                                <div>
                                    <div style="font-weight:700;color:#111;">{{ Str::limit($booking->listing->title ?? 'Listing Removed', 35) }}</div>
                                    <small class="text-muted">Qty: {{ $booking->qty ?? 1 }} unit(s)</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700;color:#111;">{{ $booking->customer->name ?? '—' }}</div>
                            <small class="text-muted">{{ $booking->customer->email ?? '' }}</small>
                        </td>
                        <td style="font-size:.85rem;">
                            <div style="font-weight:700;color:#111;">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} → {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                            </div>
                            <small class="text-muted">{{ $booking->total_days }} day(s)</small>
                        </td>
                        <td style="font-weight:900;color:#111;">
                            £{{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td>
                            <span class="status-badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <form action="{{ route('vendor.rental-bookings.status', $booking->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm" style="width:130px;border-radius:8px;font-weight:700;font-size:.78rem;" onchange="this.form.submit()">
                                        <option value="pending" @selected($booking->status === 'pending')>Pending</option>
                                        <option value="confirmed" @selected($booking->status === 'confirmed')>Confirmed</option>
                                        <option value="active" @selected($booking->status === 'active')>Active / Dispatched</option>
                                        <option value="returned" @selected($booking->status === 'returned')>Returned</option>
                                        <option value="completed" @selected($booking->status === 'completed')>Completed</option>
                                        <option value="cancelled" @selected($booking->status === 'cancelled')>Cancelled</option>
                                    </select>
                                </form>
                                <a href="/invoices/booking/{{ $booking->id }}" target="_blank" class="btn-inv" title="Print/Download Rental Invoice">
                                    <i class="bi bi-printer"></i> Invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

@endsection

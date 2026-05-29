{{-- resources/views/front/booking-detail.blade.php --}}
@extends('front.layouts.app')

@section('content')

<style>
.page-header {
    background: linear-gradient(135deg, var(--dark) 0%, #2a2a2a 100%);
    padding: 2.5rem 0;
    margin-bottom: 2.5rem;
}
.page-header h1 {
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--white);
    margin: 0;
}
.page-header .breadcrumb-item,
.page-header .breadcrumb-item a {
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .82rem;
}
.page-header .breadcrumb-item.active { color: var(--brand); }

/* ── INFO CARD ── */
.info-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.info-card-header {
    background: var(--dark);
    color: var(--white);
    padding: 1rem 1.4rem;
    font-weight: 700;
    font-size: .95rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.info-card-body { padding: 1.4rem; }

/* meta grid */
.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1rem;
}
.meta-item { }
.meta-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--muted);
    margin-bottom: .25rem;
}
.meta-value {
    font-size: .95rem;
    font-weight: 700;
    color: var(--dark);
}

/* status badges */
.badge-pending  { background: #fffbeb; border: 1.5px solid #fcd34d; color: #92400e; font-weight: 700; font-size: .78rem; padding: .3rem .85rem; border-radius: 20px; display:inline-flex; align-items:center; gap:.35rem; }
.badge-approved { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; font-weight: 700; font-size: .78rem; padding: .3rem .85rem; border-radius: 20px; display:inline-flex; align-items:center; gap:.35rem; }
.badge-rejected { background: #fff1f2; border: 1.5px solid #fecdd3; color: #9f1239; font-weight: 700; font-size: .78rem; padding: .3rem .85rem; border-radius: 20px; display:inline-flex; align-items:center; gap:.35rem; }

/* ── ITEMS TABLE ── */
.items-table { margin: 0; }
.items-table thead th {
    background: var(--dark);
    color: var(--white);
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: .9rem 1.2rem;
    border: none;
}
.items-table tbody td {
    padding: 1rem 1.2rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
    font-size: .88rem;
    color: var(--dark);
}
.items-table tbody tr:last-child td { border-bottom: none; }
.items-table tbody tr:hover td { background: #fffdf0; }

.item-name { font-weight: 600; font-size: .9rem; }
.qty-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--brand-lt);
    border: 1.5px solid var(--brand);
    color: var(--dark);
    font-weight: 700;
    font-size: .82rem;
    width: 32px; height: 32px;
    border-radius: 7px;
}
.price-cell { color: var(--muted); font-weight: 600; }
.total-cell { font-weight: 700; font-size: .95rem; }

.grand-total-row td {
    background: var(--brand-lt) !important;
    border-top: 2px solid var(--brand) !important;
    font-weight: 800 !important;
    font-size: 1.1rem !important;
}

/* back btn */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    background: var(--dark);
    color: var(--brand);
    font-weight: 700;
    font-size: .85rem;
    padding: .55rem 1.2rem;
    border-radius: 9px;
    text-decoration: none;
    transition: all .2s;
    margin-bottom: 1.5rem;
}
.btn-back:hover {
    background: var(--brand);
    color: var(--dark);
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/my-bookings">My Bookings</a></li>
                <li class="breadcrumb-item active">#{{ $booking->booking_no }}</li>
            </ol>
        </nav>
        <h1><i class="bi bi-receipt me-2" style="color:var(--brand);"></i>Booking #{{ $booking->booking_no }}</h1>
    </div>
</div>

<div class="container pb-5">

    <a href="/my-bookings" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to My Bookings
    </a>

    <!-- Booking Meta -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-info-circle-fill"></i> Booking Details
        </div>
        <div class="info-card-body">
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">Booking No.</div>
                    <div class="meta-value">#{{ $booking->booking_no }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Start Date</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">End Date</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Start Time</div>
                    <div class="meta-value">{{ $booking->start_time ?? '—' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">End Time</div>
                    <div class="meta-value">{{ $booking->end_time ?? '—' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Total Days</div>
                    <div class="meta-value">{{ $booking->total_days }} day(s)</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Status</div>
                    <div class="meta-value">
                        @if($booking->status == 'pending')
                            <span class="badge-pending"><i class="bi bi-hourglass-split"></i>Pending</span>
                        @elseif($booking->status == 'approved')
                            <span class="badge-approved"><i class="bi bi-check-circle-fill"></i>Approved</span>
                        @elseif($booking->status == 'rejected')
                            <span class="badge-rejected"><i class="bi bi-x-circle-fill"></i>Rejected</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($booking->note)
                <div class="mt-3 pt-3 border-top" style="border-color:var(--border)!important;">
                    <div class="meta-label mb-1">Special Note</div>
                    <p style="font-size:.88rem;color:var(--muted);margin:0;background:#f9f9f9;border-radius:8px;padding:.75rem 1rem;border:1px solid var(--border);">
                        {{ $booking->note }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bi bi-box-seam-fill"></i> Booked Items
        </div>
        <table class="table items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price / Day</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($booking->items as $row)
                    @php $grandTotal += $row->total_amount; @endphp
                    <tr>
                        <td>
                            <div class="item-name">{{ $row->item->title }}</div>
                        </td>
                        <td>
                            <span class="qty-pill">{{ $row->qty }}</span>
                        </td>
                        <td class="price-cell">£{{ number_format($row->price_per_day, 2) }}</td>
                        <td class="total-cell">£{{ number_format($row->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total-row">
                    <td colspan="3"><i class="bi bi-receipt me-2"></i>Grand Total</td>
                    <td>£{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

@endsection
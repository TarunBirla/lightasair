{{-- resources/views/front/my-bookings.blade.php --}}
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

/* ── TABLE WRAP ── */
.table-wrap {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}
.bookings-table { margin: 0; }
.bookings-table thead th {
    background: var(--dark);
    color: var(--white);
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 1rem 1.2rem;
    border: none;
    white-space: nowrap;
}
.bookings-table tbody td {
    padding: 1rem 1.2rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
    font-size: .88rem;
    color: var(--dark);
}
.bookings-table tbody tr:last-child td { border-bottom: none; }
.bookings-table tbody tr { transition: background .15s; }
.bookings-table tbody tr:hover td { background: #fffdf0; }

/* booking number */
.booking-no {
    font-weight: 800;
    font-size: .9rem;
    color: var(--dark);
}
.booking-no span {
    display: inline-block;
    background: var(--brand-lt);
    border: 1.5px solid var(--brand);
    border-radius: 6px;
    padding: .15rem .55rem;
    font-size: .78rem;
    font-weight: 700;
    color: var(--brand-dk);
}

/* date display */
.date-cell {
    font-size: .85rem;
    color: var(--muted);
}
.date-cell strong { display: block; color: var(--dark); font-size: .88rem; }

/* days pill */
.days-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--brand-lt);
    border: 1.5px solid var(--brand);
    color: var(--dark);
    font-weight: 700;
    font-size: .82rem;
    padding: .2rem .65rem;
    border-radius: 20px;
    gap: .3rem;
}

/* amount */
.amount-cell {
    font-weight: 700;
    font-size: .95rem;
    color: var(--dark);
}

/* status badges */
.badge-pending  { background: #fffbeb; border: 1.5px solid #fcd34d; color: #92400e; font-weight: 700; font-size: .75rem; padding: .3rem .75rem; border-radius: 20px; }
.badge-approved { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; font-weight: 700; font-size: .75rem; padding: .3rem .75rem; border-radius: 20px; }
.badge-rejected { background: #fff1f2; border: 1.5px solid #fecdd3; color: #9f1239; font-weight: 700; font-size: .75rem; padding: .3rem .75rem; border-radius: 20px; }

/* view btn */
.btn-view {
    background: var(--dark);
    color: var(--brand);
    font-size: .78rem;
    font-weight: 700;
    padding: .4rem .9rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    white-space: nowrap;
}
.btn-view:hover {
    background: var(--brand);
    color: var(--dark);
}

/* empty state */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}
.empty-icon {
    width: 90px; height: 90px;
    background: var(--brand-lt);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--brand-dk);
    margin: 0 auto 1.5rem;
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item active">My Bookings</li>
            </ol>
        </nav>
        <h1><i class="bi bi-calendar2-week me-2" style="color:var(--brand);"></i>My Bookings</h1>
    </div>
</div>

<div class="container pb-5">

    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
             style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;">
            <i class="bi bi-check-circle-fill fs-5" style="color:var(--success);"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrap">
        @if(count($bookings) > 0)
            <table class="table bookings-table">
                <thead>
                    <tr>
                        <th>Booking No.</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <span class="booking-no">
                                    <span>#{{ $booking->booking_no }}</span>
                                </span>
                            </td>
                            <td class="date-cell">
                                <strong>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</strong>
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('l') }}
                            </td>
                            <td class="date-cell">
                                <strong>{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</strong>
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('l') }}
                            </td>
                            <td>
                                <span class="days-pill">
                                    <i class="bi bi-clock"></i> {{ $booking->total_days }}d
                                </span>
                            </td>
                            <td class="amount-cell">£{{ number_format($booking->total_amount, 2) }}</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge-pending"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                @elseif($booking->status == 'approved')
                                    <span class="badge-approved"><i class="bi bi-check-circle-fill me-1"></i>Approved</span>
                                @elseif($booking->status == 'rejected')
                                    <span class="badge-rejected"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="/booking/{{ $booking->id }}" class="btn-view">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                <h4 style="font-weight:800;">No Bookings Yet</h4>
                <p style="color:var(--muted);max-width:340px;margin:0 auto 1.5rem;">
                    You haven't made any rental bookings yet. Browse our equipment and place your first order.
                </p>
                <a href="/#items" class="btn-brand text-decoration-none d-inline-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Browse Equipment
                </a>
            </div>
        @endif
    </div>

</div>

@endsection
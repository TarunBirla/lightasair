@extends('layouts.admin')

@section('page-title', 'Bookings')
@section('breadcrumb', 'Admin / Bookings')

@section('content')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: #111;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .status-filter-group {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #E8E6DF;
        background: #fff;
        color: #888;
        font-family: 'DM Sans', sans-serif;
        transition: all .2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #FFC700;
        border-color: #FFC700;
        color: #111;
    }

    .filter-btn.f-pending.active   { background: #FFF3E0; border-color: #FFA000; color: #E65100; }
    .filter-btn.f-approved.active  { background: #EDFAF0; border-color: #2E7D32; color: #1a7a3a; }
    .filter-btn.f-completed.active { background: #EAF3FF; border-color: #1565C0; color: #1a5fb4; }
    .filter-btn.f-rejected.active  { background: #FEF0F0; border-color: #c0392b; color: #c0392b; }

    .table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-bottom: 1px solid #F0EEE8;
        gap: 12px;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        max-width: 280px;
    }

    .search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #ccc;
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border: 1px solid #E8E6DF;
        border-radius: 9px;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        color: #111;
        background: #FAFAF8;
        outline: none;
        transition: border-color .2s;
    }

    .search-input:focus {
        border-color: #FFC700;
        background: #fff;
    }

    .table-info-text {
        font-size: 12px;
        color: #aaa;
        font-weight: 500;
        white-space: nowrap;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead tr {
        background: #FAFAF8;
        border-bottom: 1px solid #F0EEE8;
    }

    .table-card thead th {
        padding: 13px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #888;
        text-align: left;
        white-space: nowrap;
    }

    .table-card tbody tr {
        border-bottom: 1px solid #F7F6F1;
        transition: background .15s;
    }

    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #FFFDF5; }

    .table-card tbody td {
        padding: 13px 14px;
        font-size: 13px;
        color: #111;
        vertical-align: middle;
    }

    .id-badge {
        display: inline-block;
        background: #F7F6F1;
        color: #888;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .booking-no {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 13px;
        color: #111;
        background: #FFF3B0;
        padding: 4px 10px;
        border-radius: 7px;
        letter-spacing: .5px;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .mini-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: #FFF3B0;
        color: #B38A00;
        font-size: 12px;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid #FFC700;
    }

    .user-name-sm { font-weight: 600; font-size: 13px; }
    .user-email-sm { font-size: 11px; color: #aaa; margin-top: 1px; }

    .date-range {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .date-range .dr-from,
    .date-range .dr-to {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
        color: #555;
    }

    .date-range .dr-from i { color: #2E7D32; }
    .date-range .dr-to   i { color: #c0392b; }

    .days-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #F7F6F1;
        color: #555;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .amount {
        font-weight: 700;
        font-size: 15px;
        color: #111;
    }

    .amount span {
        font-size: 12px;
        color: #888;
        font-weight: 400;
    }

    /* Status badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .bs-pending   { background: #FFF3E0; color: #E65100; }
    .bs-approved  { background: #EDFAF0; color: #1a7a3a; }
    .bs-completed { background: #EAF3FF; color: #1a5fb4; }
    .bs-rejected  { background: #FEF0F0; color: #c0392b; }
    .bs-unknown   { background: #F5F5F5; color: #888; }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        background: #FFF3B0;
        color: #B38A00;
        border: none;
        font-family: 'DM Sans', sans-serif;
        transition: background .2s;
        white-space: nowrap;
    }

    .btn-view:hover {
        background: #FFC700;
        color: #111;
    }

    .empty-row td {
        text-align: center;
        padding: 60px 0;
        color: #bbb;
        font-size: 14px;
    }

    .empty-row i {
        font-size: 36px;
        display: block;
        margin-bottom: 10px;
        color: #e0e0e0;
    }
</style>

<div class="page-header">
    <h3>
        <i class="fa-solid fa-calendar-check" style="color:#FFC700"></i>
        Bookings
    </h3>
    <div class="status-filter-group">
        <button class="filter-btn active" onclick="filterStatus('all', this)">All</button>
        <button class="filter-btn f-pending"   onclick="filterStatus('pending', this)">
            <i class="fa-solid fa-clock" style="font-size:10px;margin-right:4px"></i>Pending
        </button>
        <button class="filter-btn f-approved"  onclick="filterStatus('approved', this)">
            <i class="fa-solid fa-check" style="font-size:10px;margin-right:4px"></i>Approved
        </button>
        <button class="filter-btn f-completed" onclick="filterStatus('completed', this)">
            <i class="fa-solid fa-flag-checkered" style="font-size:10px;margin-right:4px"></i>Completed
        </button>
        <button class="filter-btn f-rejected"  onclick="filterStatus('rejected', this)">
            <i class="fa-solid fa-xmark" style="font-size:10px;margin-right:4px"></i>Rejected
        </button>
    </div>
</div>

<div class="table-card">

    <div class="table-toolbar">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-input" placeholder="Search bookings, users..." id="bookingSearch">
        </div>
        <div class="table-info-text">
            {{ count($bookings) }} total bookings
        </div>
    </div>

    <table id="bookingsTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Booking No</th>
                <th>User</th>
                <th>Dates</th>
                <th>Days</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr data-status="{{ $booking->status }}">
                <td><span class="id-badge">{{ $booking->id }}</span></td>

                <td><span class="booking-no">#{{ $booking->booking_no }}</span></td>

                <td>
                    <div class="user-cell">
                        <div class="mini-avatar">
                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name-sm">{{ $booking->user->name ?? '—' }}</div>
                            <div class="user-email-sm">{{ $booking->user->email ?? '' }}</div>
                        </div>
                    </div>
                </td>

                <td>
                    <div class="date-range">
                        <div class="dr-from">
                            <i class="fa-solid fa-arrow-right-to-bracket" style="font-size:11px"></i>
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                        </div>
                        <div class="dr-to">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:11px"></i>
                            {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                        </div>
                    </div>
                </td>

                <td>
                    <span class="days-pill">
                        <i class="fa-regular fa-sun" style="font-size:11px;color:#FFC700"></i>
                        {{ $booking->total_days }}d
                    </span>
                </td>

                <td>
                    <span class="amount">£{{ number_format($booking->total_amount, 2) }}</span>
                </td>

                <td>
                    @if($booking->status == 'pending')
                        <span class="badge-status bs-pending">
                            <i class="fa-solid fa-clock" style="font-size:9px"></i> Pending
                        </span>
                    @elseif($booking->status == 'approved')
                        <span class="badge-status bs-approved">
                            <i class="fa-solid fa-circle-check" style="font-size:9px"></i> Approved
                        </span>
                    @elseif($booking->status == 'completed')
                        <span class="badge-status bs-completed">
                            <i class="fa-solid fa-flag-checkered" style="font-size:9px"></i> Completed
                        </span>
                    @elseif($booking->status == 'rejected')
                        <span class="badge-status bs-rejected">
                            <i class="fa-solid fa-circle-xmark" style="font-size:9px"></i> Rejected
                        </span>
                    @else
                        <span class="badge-status bs-unknown">Unknown</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-view">
                        <i class="fa-solid fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="8">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    No bookings found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function filterStatus(status, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#bookingsTable tbody tr').forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = (row.dataset.status === status) ? '' : 'none';
        }
    });
}

document.getElementById('bookingSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#bookingsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

@endsection
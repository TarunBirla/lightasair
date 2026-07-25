@extends('layouts.admin')

@section('page-title', 'Rental Bookings')
@section('breadcrumb', 'Admin / Rentals / All Bookings')

@section('content')

<style>
.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.4rem; align-items: center; }
.toolbar form { display: flex; gap: .6rem; flex-wrap: wrap; }
.f-select { padding: .5rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .85rem; background: #fff; outline: none; font-family: inherit; }
.f-select:focus { border-color: #FFC700; }
.btn-go  { padding: .5rem 1.1rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; }
.btn-clr { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; text-decoration: none; color: #555; }

.bk-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.bk-table th {
    background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF;
}
.bk-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.bk-table tr:last-child td { border: none; }
.bk-table tr:hover td { background: #fefef9; }

.bk-badge { padding: .22rem .7rem; border-radius: 20px; font-size: .7rem; font-weight: 800; display: inline-block; }
.bk-pending   { background: #fff3cd; color: #856404; }
.bk-confirmed { background: #d4edda; color: #155724; }
.bk-active    { background: #cfe2ff; color: #084298; }
.bk-returned  { background: #e2d9f3; color: #4a2475; }
.bk-completed { background: #e9ecef; color: #495057; }
.bk-cancelled { background: #f8d7da; color: #721c24; }

.empty-td { text-align: center; padding: 3rem; color: #999; }
.pg-wrap  { margin-top: 1.2rem; display: flex; justify-content: flex-end; }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;">
    <h2 style="font-size:1.25rem;font-weight:800;">All Rental Bookings</h2>
    <a href="/admin/rentals" style="font-size:.85rem;color:#888;text-decoration:none;"><i class="fa-solid fa-arrow-left me-1"></i>Back to Listings</a>
</div>

{{-- Filters --}}
<div class="toolbar">
    <form method="GET" action="/admin/rental-bookings">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            @foreach(['pending','confirmed','active','returned','completed','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="/admin/rental-bookings" class="btn-clr">Reset</a>
    </form>
</div>

<table class="bk-table">
    <thead>
        <tr>
            <th>Ref</th>
            <th>Equipment</th>
            <th>Customer</th>
            <th>Vendor</th>
            <th>Dates</th>
            <th>Days</th>
            <th>Total</th>
            <th>Status</th>
            <th>Booked</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $bk)
        <tr>
            <td style="font-family:monospace;font-size:.8rem;font-weight:700;color:#FFC700;">{{ $bk->booking_ref }}</td>
            <td>
                <div style="font-weight:700;font-size:.85rem;">{{ Str::limit($bk->listing->title ?? 'Deleted Listing', 35) }}</div>
                <div style="font-size:.72rem;color:#aaa;">£{{ number_format($bk->price_per_day, 2) }}/day × {{ $bk->qty }} unit(s)</div>
            </td>
            <td>
                <div style="font-weight:600;font-size:.85rem;">{{ $bk->customer->name ?? '—' }}</div>
                <div style="font-size:.72rem;color:#aaa;">{{ $bk->customer->email ?? '' }}</div>
            </td>
            <td style="font-size:.85rem;">{{ $bk->vendor->name ?? '—' }}</td>
            <td style="font-size:.82rem;">
                {{ \Carbon\Carbon::parse($bk->start_date)->format('d M Y') }}<br>
                <span style="color:#888;">→ {{ \Carbon\Carbon::parse($bk->end_date)->format('d M Y') }}</span>
            </td>
            <td style="font-weight:700;text-align:center;">{{ $bk->total_days }}</td>
            <td style="font-weight:800;">£{{ number_format($bk->total_amount, 2) }}</td>
            <td><span class="bk-badge bk-{{ $bk->status }}">{{ ucfirst($bk->status) }}</span></td>
            <td style="font-size:.8rem;color:#888;">{{ $bk->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="9"><div class="empty-td"><i class="fa-solid fa-calendar-xmark" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>No bookings found.</div></td></tr>
        @endforelse
    </tbody>
</table>

<div class="pg-wrap">{{ $bookings->appends(request()->query())->links() }}</div>

@endsection

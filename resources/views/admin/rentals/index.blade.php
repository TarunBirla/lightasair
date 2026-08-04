@extends('layouts.admin')

@section('page-title', 'Rental Listings')
@section('breadcrumb', 'Admin / Rentals / All Listings')

@section('content')

<style>
.stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 1.8rem; }
.stat-box {
    background: #fff; border-radius: 12px; padding: 1.2rem 1.4rem;
    border: 1px solid #E8E6DF; display: flex; align-items: center; gap: 1rem;
}
.stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.stat-icon.yellow { background: #FFF3B0; color: #B38A00; }
.stat-icon.green  { background: #EDFAF0; color: #1a7a3a; }
.stat-icon.blue   { background: #EAF3FF; color: #1a5fb4; }
.stat-num   { font-size: 1.7rem; font-weight: 800; color: #111; line-height: 1; }
.stat-label { font-size: .75rem; color: #888; margin-top: .15rem; }

.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.4rem; align-items: center; }
.toolbar form { display: flex; gap: .6rem; flex-wrap: wrap; }
.f-input, .f-select {
    padding: .5rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px;
    font-size: .85rem; background: #fff; outline: none; font-family: inherit;
}
.f-input:focus, .f-select:focus { border-color: #FFC700; }
.btn-go   { padding: .5rem 1.1rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; }
.btn-clr  { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; text-decoration: none; color: #555; }

.listing-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.listing-table th {
    background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF;
}
.listing-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.listing-table tr:last-child td { border: none; }
.listing-table tr:hover td { background: #fefef9; }

.listing-thumb { width: 50px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #f0ede8; }
.listing-thumb-ph { width: 50px; height: 44px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #ccc; }

.sbadge { padding: .22rem .7rem; border-radius: 20px; font-size: .7rem; font-weight: 800; display: inline-block; }
.s-pending  { background: #fff3cd; color: #856404; }
.s-approved { background: #d4edda; color: #155724; }
.s-rejected { background: #f8d7da; color: #721c24; }
.s-draft    { background: #e9ecef; color: #6c757d; }

.act-btns { display: flex; gap: .4rem; flex-wrap: wrap; }
.ab { padding: .3rem .65rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: .25rem; }
.ab-view    { background: #f3f4f6; color: #374151; }
.ab-view:hover { background: #e5e7eb; }
.ab-approve { background: #d4edda; color: #155724; }
.ab-approve:hover { background: #c3e6cb; }
.ab-reject  { background: #f8d7da; color: #721c24; }
.ab-reject:hover { background: #f5c6cb; }
.ab-feature { background: #FFF3B0; color: #B38A00; }
.ab-feature:hover { background: #ffe066; }
.ab-delete  { background: #fee2e2; color: #991b1b; }
.ab-delete:hover { background: #fecaca; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 440px; max-width: 95vw; }
.reject-box h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; }
.reject-ta { width: 100%; padding: .7rem; border: 1.5px solid #e8e6e0; border-radius: 8px; font-size: .9rem; min-height: 100px; font-family: inherit; resize: vertical; }
.reject-ta:focus { outline: none; border-color: #FFC700; }
.reject-actions { display: flex; gap: .6rem; margin-top: 1rem; justify-content: flex-end; }
.empty-td { text-align: center; padding: 3rem; color: #999; }
.pg-wrap { margin-top: 1.2rem; display: flex; justify-content: flex-end; }
</style>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-num">{{ $stats['pending'] }}</div><div class="stat-label">Pending Review</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-num">{{ $stats['approved'] }}</div><div class="stat-label">Live Rentals</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-days"></i></div>
        <div><div class="stat-num">{{ $stats['total'] }}</div><div class="stat-label">Total Listings</div></div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <form method="GET" action="/admin/rentals">
        <input type="text" name="search" class="f-input" placeholder="Search title…" value="{{ request('search') }}">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            @foreach(['pending','approved','rejected','draft','inactive'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="/admin/rentals" class="btn-clr">Reset</a>
    </form>
    <a href="/admin/rental-bookings" class="btn-go" style="background:#111;color:#FFC700;text-decoration:none;">
        <i class="fa-solid fa-calendar-check me-1"></i> View Bookings
    </a>
</div>

{{-- Table --}}
<table class="listing-table">
    <thead>
        <tr>
            <th>Equipment</th>
            <th>Vendor</th>
            <th>Price/Day</th>
            <th>Qty</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($listings as $listing)
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:.7rem;">
                    <img src="{{ $listing->primaryImageUrl() }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #eee;" alt="" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'48\' height=\'48\' fill=\'%23eee\'><text x=\'24\' y=\'28\' font-family=\'sans-serif\' font-size=\'10\' fill=\'%23888\' text-anchor=\'middle\'>Img</text></svg>'">
                    <div>
                        <div style="font-weight:700;font-size:.88rem;line-height:1.3;">{{ Str::limit($listing->title, 45) }}</div>
                        <div style="font-size:.73rem;color:#aaa;">{{ $listing->category->name ?? 'Uncategorised' }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div style="font-weight:600;font-size:.85rem;">{{ $listing->vendor->name }}</div>
                <div style="font-size:.73rem;color:#aaa;">{{ $listing->vendor->email }}</div>
            </td>
            <td style="font-weight:700;">£{{ number_format($listing->price_per_day, 2) }}
                @if($listing->price_per_week)<br><small style="color:#888;font-weight:400;">£{{ number_format($listing->price_per_week, 2) }}/wk</small>@endif
            </td>
            <td style="font-weight:600;">{{ $listing->total_qty }}</td>
            <td><span class="sbadge s-{{ $listing->status }}">{{ ucfirst($listing->status) }}</span></td>
            <td style="font-size:.8rem;color:#888;">{{ $listing->created_at->format('d M Y') }}</td>
            <td>
                <div class="act-btns">
                    <a href="/admin/rentals/{{ $listing->id }}" class="ab ab-view"><i class="fa-solid fa-eye"></i></a>
                    @if($listing->isPending())
                        <form action="/admin/rentals/{{ $listing->id }}/approve" method="POST" style="display:inline">
                            @csrf <button type="submit" class="ab ab-approve" title="Approve"><i class="fa-solid fa-check"></i></button>
                        </form>
                        <button onclick="openReject({{ $listing->id }})" class="ab ab-reject" title="Reject"><i class="fa-solid fa-times"></i></button>
                    @endif
                    @if($listing->isApproved())
                        <form action="/admin/rentals/{{ $listing->id }}/toggle-featured" method="POST" style="display:inline">
                            @csrf <button type="submit" class="ab ab-feature" title="{{ $listing->is_featured ? 'Unfeature' : 'Feature' }}"><i class="fa-solid fa-star"></i></button>
                        </form>
                    @endif
                    <form action="/admin/rentals/{{ $listing->id }}" method="POST"
                          onsubmit="return confirm('Delete this listing?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="ab ab-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="empty-td"><i class="fa-solid fa-calendar-days" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>No rental listings found.</div></td></tr>
        @endforelse
    </tbody>
</table>

<div class="pg-wrap">{{ $listings->appends(request()->query())->links() }}</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-box">
        <h3><i class="fa-solid fa-times-circle me-2" style="color:#dc2626;"></i>Reject Rental Listing</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" class="reject-ta" placeholder="Provide a clear reason (min 10 characters)…" required minlength="10"></textarea>
            <div class="reject-actions">
                <button type="button" onclick="closeReject()" class="btn-clr">Cancel</button>
                <button type="submit" class="btn-go" style="background:#dc2626;color:#fff;">Reject Listing</button>
            </div>
        </form>
    </div>
</div>
<script>
function openReject(id) {
    document.getElementById('rejectForm').action = '/admin/rentals/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}
function closeReject() { document.getElementById('rejectModal').classList.remove('open'); }
document.getElementById('rejectModal').addEventListener('click', function(e) { if(e.target===this) closeReject(); });
</script>

@endsection

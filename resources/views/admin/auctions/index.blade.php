@extends('layouts.admin')

@section('page-title', 'Auctions Management')
@section('breadcrumb', 'Admin / Auctions / Overview')

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

.auc-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.auc-table th { background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF; }
.auc-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.auc-table tr:last-child td { border: none; }
.auc-table tr:hover td { background: #fefef9; }

.ab { padding: .3rem .65rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: .25rem; }
.ab-view    { background: #f3f4f6; color: #374151; }
.ab-approve { background: #d4edda; color: #155724; }
.ab-reject  { background: #f8d7da; color: #721c24; }
.ab-close   { background: #111; color: #FFC700; }
.ab-delete  { background: #fee2e2; color: #991b1b; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 440px; max-width: 95vw; }
</style>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-num">{{ $stats['pending'] }}</div><div class="stat-label">Pending Approval</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-play"></i></div>
        <div><div class="stat-num">{{ $stats['active'] }}</div><div class="stat-label">Active Auctions</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon purple"><i class="fa-solid fa-flag-checkered"></i></div>
        <div><div class="stat-num">{{ $stats['ended'] }}</div><div class="stat-label">Ended Auctions</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-gavel"></i></div>
        <div><div class="stat-num">{{ $stats['total'] }}</div><div class="stat-label">Total Auctions</div></div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <form method="GET" action="/admin/auctions">
        <input type="text" name="search" class="f-input" placeholder="Search auction title…" value="{{ request('search') }}">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            @foreach(['pending','active','ended','rejected','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="/admin/auctions" class="btn-clr">Reset</a>
    </form>
</div>

<table class="auc-table">
    <thead>
        <tr>
            <th>Auction</th>
            <th>Vendor</th>
            <th>Current Bid</th>
            <th>Reserve</th>
            <th>Bids</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($auctions as $auction)
        <tr>
            <td>
                <div style="font-weight:700;font-size:.88rem;">{{ Str::limit($auction->title, 45) }}</div>
                <div style="font-size:.73rem;color:#aaa;">{{ $auction->category->name ?? 'Uncategorised' }}</div>
            </td>
            <td>
                <div style="font-weight:600;font-size:.85rem;">{{ $auction->vendor->name }}</div>
            </td>
            <td style="font-weight:800;color:#16a34a;">£{{ number_format($auction->current_bid, 2) }}</td>
            <td style="font-size:.85rem;">£{{ number_format($auction->reserve_price, 2) }}</td>
            <td style="font-weight:700;">{{ $auction->bid_count }}</td>
            <td><span class="badge bg-secondary">{{ ucfirst($auction->status) }}</span></td>
            <td>
                <div style="display:flex;gap:.3rem;">
                    <a href="/admin/auctions/{{ $auction->id }}" class="ab ab-view"><i class="fa-solid fa-eye"></i></a>
                    @if($auction->isPending())
                        <form action="/admin/auctions/{{ $auction->id }}/approve" method="POST" style="display:inline">
                            @csrf <button type="submit" class="ab ab-approve" title="Approve"><i class="fa-solid fa-check"></i></button>
                        </form>
                        <button onclick="openReject({{ $auction->id }})" class="ab ab-reject" title="Reject"><i class="fa-solid fa-times"></i></button>
                    @endif
                    @if($auction->isActive())
                        <form action="/admin/auctions/{{ $auction->id }}/close" method="POST" style="display:inline">
                            @csrf <button type="submit" class="ab ab-close" title="Close Auction"><i class="fa-solid fa-lock"></i></button>
                        </form>
                    @endif
                    <form action="/admin/auctions/{{ $auction->id }}" method="POST" onsubmit="return confirm('Delete this auction?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="ab ab-delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center p-4 text-muted">No auctions found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">{{ $auctions->appends(request()->query())->links() }}</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-box">
        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Reject Auction</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" class="form-control mb-3" placeholder="Reason for rejection (min 10 characters)..." required minlength="10" rows="3"></textarea>
            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                <button type="button" onclick="closeReject()" class="btn btn-light">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject Auction</button>
            </div>
        </form>
    </div>
</div>
<script>
function openReject(id) {
    document.getElementById('rejectForm').action = '/admin/auctions/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}
function closeReject() { document.getElementById('rejectModal').classList.remove('open'); }
</script>

@endsection

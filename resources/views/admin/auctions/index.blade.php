@extends('layouts.admin')

@section('page-title', 'Auctions Management')
@section('breadcrumb', 'Admin / Auctions / Timed Auctions')

@section('content')

<style>
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.8rem; }
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

.ab { padding: .35rem .7rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: .3rem; }
.ab-view    { background: #f3f4f6; color: #374151; }
.ab-approve { background: #d4edda; color: #155724; }
.ab-reject  { background: #f8d7da; color: #721c24; }
.ab-close   { background: #111; color: #FFC700; }
.ab-delete  { background: #fee2e2; color: #991b1b; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 450px; max-width: 95vw; }
</style>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success mb-3" style="border-radius:10px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3" style="border-radius:10px;">{{ session('error') }}</div>
@endif

{{-- Stats Row --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-num">{{ $stats['pending'] ?? 0 }}</div><div class="stat-label">Pending Approval</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-play"></i></div>
        <div><div class="stat-num">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active Auctions</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon purple"><i class="fa-solid fa-flag-checkered"></i></div>
        <div><div class="stat-num">{{ $stats['ended'] ?? 0 }}</div><div class="stat-label">Ended Auctions</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-gavel"></i></div>
        <div><div class="stat-num">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Auctions</div></div>
    </div>
</div>

{{-- Filter Toolbar --}}
<div class="toolbar">
    <form method="GET" action="/admin/auctions">
        <input type="text" name="search" class="f-input" placeholder="Search auction title..." value="{{ request('search') }}">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            @foreach(['pending' => 'Pending Approval', 'active' => 'Active Bidding', 'ended' => 'Ended', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="/admin/auctions" class="btn-clr">Reset</a>
    </form>
</div>

{{-- Data Table --}}
<div style="background:#fff;border-radius:12px;overflow:hidden;border:1px solid #E8E6DF;">
    <table class="auc-table">
        <thead>
            <tr>
                <th style="width:60px;">Image</th>
                <th>Auction Title / Vendor</th>
                <th>Current Bid</th>
                <th>Reserve</th>
                <th>Bids</th>
                <th>Deadline / End Time</th>
                <th>Status</th>
                <th style="width:160px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($auctions as $auction)
            <tr>
                <td>
                    <img src="{{ $auction->primaryImageUrl() }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #eee;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'48\' height=\'48\' fill=\'%23eee\'><text x=\'24\' y=\'28\' font-family=\'sans-serif\' font-size=\'10\' fill=\'%23888\' text-anchor=\'middle\'>Img</text></svg>'">
                </td>
                <td>
                    <div style="font-weight:700;font-size:.88rem;color:#111;">{{ Str::limit($auction->title, 45) }}</div>
                    <div style="font-size:.76rem;color:#888;margin-top:.1rem;">
                        <i class="fa-solid fa-store me-1"></i> Vendor: <strong>{{ $auction->vendor->name ?? 'Unknown' }}</strong>
                        @if($auction->category) · {{ $auction->category->name }} @endif
                    </div>
                </td>
                <td style="font-weight:800;color:#16a34a;font-size:.95rem;">
                    £{{ number_format($auction->current_bid, 2) }}
                </td>
                <td style="font-size:.84rem;">
                    £{{ number_format($auction->reserve_price, 2) }}
                    @if($auction->reserveMet())
                        <span class="badge bg-success" style="font-size:.65rem;display:block;margin-top:.1rem;">Met</span>
                    @else
                        <span class="badge bg-light text-muted border" style="font-size:.65rem;display:block;margin-top:.1rem;">Not Met</span>
                    @endif
                </td>
                <td style="font-weight:800;font-size:.85rem;">{{ $auction->bid_count }} bid(s)</td>
                <td style="font-size:.82rem;color:#555;">
                    {{ $auction->end_time ? $auction->end_time->format('d M Y, H:i') : '—' }}
                </td>
                <td>
                    @if($auction->isActive())
                        <span class="badge bg-success" style="font-size:.72rem;">Active</span>
                    @elseif($auction->isPending())
                        <span class="badge bg-warning text-dark" style="font-size:.72rem;">Pending</span>
                    @elseif($auction->isEnded())
                        <span class="badge bg-secondary" style="font-size:.72rem;">Ended</span>
                    @elseif($auction->isRejected())
                        <span class="badge bg-danger" style="font-size:.72rem;">Rejected</span>
                    @else
                        <span class="badge bg-secondary" style="font-size:.72rem;">{{ ucfirst($auction->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:.3rem;align-items:center;">
                        <a href="{{ route('admin.auctions.show', $auction->id) }}" class="ab ab-view" title="View Feed & History"><i class="fa-solid fa-eye"></i></a>
                        @if($auction->isPending())
                            <form action="{{ route('admin.auctions.approve', $auction->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="ab ab-approve" title="Approve"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <button type="button" class="ab ab-reject" title="Reject" onclick="openReject({{ $auction->id }}, '{{ addslashes($auction->title) }}')">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        @elseif($auction->isActive())
                            <form action="{{ route('admin.auctions.close', $auction->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Close this auction now?')">
                                @csrf
                                <button type="submit" class="ab ab-close" title="Close Auction"><i class="fa-solid fa-gavel"></i></button>
                            </form>
                        @endif
                        @if($auction->bid_count == 0)
                            <form action="{{ route('admin.auctions.destroy', $auction->id) }}" method="POST" onsubmit="return confirm('Permanently delete this auction?')" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ab ab-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center p-4 text-muted">No auction listings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $auctions->appends(request()->query())->links() }}
</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-box">
        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:.5rem;color:#111;"><i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Reject Auction</h3>
        <p style="font-size:.85rem;color:#888;margin-bottom:1rem;" id="rejectModalSubtitle">Please provide a reason for rejection:</p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="rejection_reason" class="form-control" rows="4" required minlength="10" placeholder="Explain why this auction is rejected..." style="border-radius:10px;font-size:.88rem;"></textarea>
            </div>
            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                <button type="button" onclick="closeReject()" class="btn btn-light" style="border-radius:8px;font-weight:600;font-size:.85rem;">Cancel</button>
                <button type="submit" class="btn btn-danger" style="border-radius:8px;font-weight:700;font-size:.85rem;">Reject Auction</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReject(id, title) {
    document.getElementById('rejectForm').action = '/admin/auctions/' + id + '/reject';
    document.getElementById('rejectModalSubtitle').textContent = 'Rejecting: ' + title;
    document.getElementById('rejectModal').classList.add('open');
}
function closeReject() {
    document.getElementById('rejectModal').classList.remove('open');
}
</script>

@endsection

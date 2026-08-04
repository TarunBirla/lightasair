@extends('layouts.admin')

@section('page-title', 'Vendor Management')
@section('breadcrumb', 'Admin / Vendors / Overview')

@section('content')

<style>
.stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 1.8rem; }
.stat-box { background: #fff; border-radius: 12px; padding: 1.2rem 1.4rem; border: 1px solid #E8E6DF; display: flex; align-items: center; gap: 1rem; }
.stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.stat-icon.yellow { background: #FFF3B0; color: #B38A00; }
.stat-icon.green  { background: #EDFAF0; color: #1a7a3a; }
.stat-icon.red    { background: #FEF0F0; color: #c0392b; }
.stat-icon.blue   { background: #EAF3FF; color: #1a5fb4; }
.stat-num   { font-size: 1.7rem; font-weight: 800; color: #111; line-height: 1; }
.stat-label { font-size: .75rem; color: #888; margin-top: .15rem; }

.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.4rem; align-items: center; }
.toolbar form { display: flex; gap: .6rem; flex-wrap: wrap; }
.f-input, .f-select { padding: .5rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .85rem; background: #fff; outline: none; font-family: inherit; }
.f-input:focus, .f-select:focus { border-color: #FFC700; }
.btn-go  { padding: .5rem 1.1rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; }
.btn-clr { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; text-decoration: none; color: #555; }

.v-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.v-table th { background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF; }
.v-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.v-table tr:last-child td { border: none; }
.v-table tr:hover td { background: #fefef9; }

.ab { padding: .3rem .65rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: .25rem; }
.ab-view    { background: #f3f4f6; color: #374151; }
.ab-approve { background: #d4edda; color: #155724; }
.ab-reject  { background: #f8d7da; color: #721c24; }
.ab-suspend { background: #fee2e2; color: #991b1b; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 440px; max-width: 95vw; }
</style>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
        <div><div class="stat-num">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Vendors</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-num">{{ $stats['pending'] ?? 0 }}</div><div class="stat-label">Pending Approval</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-check"></i></div>
        <div><div class="stat-num">{{ $stats['approved'] ?? 0 }}</div><div class="stat-label">Approved</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div><div class="stat-num">{{ $stats['suspended'] ?? 0 }}</div><div class="stat-label">Suspended</div></div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <form method="GET" action="/admin/vendors">
        <input type="text" name="search" class="f-input" placeholder="Search vendor name..." value="{{ request('search') }}">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            @foreach(['pending','approved','rejected','suspended'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="/admin/vendors" class="btn-clr">Reset</a>
    </form>
</div>

<div class="table-responsive">
    <table class="v-table">
        <thead>
            <tr>
                <th>Vendor</th>
                <th>Email</th>
                <th>Status</th>
                <th>Listings</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendors ?? [] as $vendor)
            <tr>
                <td>
                    <div style="font-weight:700;font-size:.88rem;">{{ $vendor->name }}</div>
                    <div style="font-size:.73rem;color:#aaa;">{{ $vendor->vendorProfile->business_name ?? 'N/A' }}</div>
                </td>
                <td>
                    <div style="font-size:.85rem;">{{ $vendor->email }}</div>
                </td>
                <td>
                    @php
                        $statusClass = 'bg-secondary';
                        $status = $vendor->vendor_status ?? 'pending';
                        if($status == 'approved') $statusClass = 'bg-success';
                        if($status == 'pending') $statusClass = 'bg-warning text-dark';
                        if($status == 'rejected' || $status == 'suspended') $statusClass = 'bg-danger';
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                </td>
                <td style="font-weight:700;">{{ $vendor->listings_count ?? 0 }}</td>
                <td>{{ $vendor->created_at ? $vendor->created_at->format('M d, Y') : 'N/A' }}</td>
                <td>
                    <div style="display:flex;gap:.3rem;">
                        <a href="/admin/vendors/{{ $vendor->id }}" class="ab ab-view" title="View"><i class="fa-solid fa-eye"></i></a>
                        
                        @if(($vendor->vendor_status ?? 'pending') == 'pending')
                            <form action="/admin/vendors/{{ $vendor->id }}/approve" method="POST" style="display:inline">
                                @csrf 
                                <button type="submit" class="ab ab-approve" title="Approve"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <button onclick="openReject({{ $vendor->id }})" class="ab ab-reject" title="Reject"><i class="fa-solid fa-times"></i></button>
                        @endif
                        
                        @if(($vendor->vendor_status ?? '') == 'approved')
                            <form action="/admin/vendors/{{ $vendor->id }}/suspend" method="POST" onsubmit="return confirm('Suspend this vendor?')" style="display:inline">
                                @csrf 
                                <button type="submit" class="ab ab-suspend" title="Suspend"><i class="fa-solid fa-ban"></i></button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center p-4 text-muted">No vendors found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($vendors) && $vendors instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3">{{ $vendors->appends(request()->query())->links() }}</div>
@endif

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-box">
        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Reject Vendor</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" class="form-control mb-3" placeholder="Reason for rejection (min 10 characters)..." required minlength="10" rows="3"></textarea>
            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                <button type="button" onclick="closeReject()" class="btn btn-light">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject Vendor</button>
            </div>
        </form>
    </div>
</div>
<script>
function openReject(id) {
    document.getElementById('rejectForm').action = '/admin/vendors/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('open');
}
function closeReject() { document.getElementById('rejectModal').classList.remove('open'); }
</script>

@endsection

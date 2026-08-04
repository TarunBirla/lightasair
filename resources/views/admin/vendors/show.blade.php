@extends('layouts.admin')

@section('page-title', 'Vendor Details')
@section('breadcrumb', 'Admin / Vendors / ' . ($vendor->name ?? 'Details'))

@section('content')
<style>
.detail-card { background: #fff; border-radius: 12px; border: 1px solid #E8E6DF; overflow: hidden; margin-bottom: 1.5rem; }
.detail-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #E8E6DF; display: flex; justify-content: space-between; align-items: center; background: #fdfdfc; }
.detail-title { font-weight: 800; font-size: 1.05rem; }
.detail-body { padding: 1.5rem; }
.info-row { display: flex; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f5f4f0; }
.info-row:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
.info-label { width: 140px; font-weight: 700; color: #555; font-size: .85rem; }
.info-val { flex: 1; font-size: .9rem; color: #111; }

.stat-box-small { background: #fdfdfc; border: 1px solid #E8E6DF; border-radius: 10px; padding: 1rem; text-align: center; }
.stat-box-small .num { font-size: 1.5rem; font-weight: 800; color: #111; line-height: 1; margin-bottom: .2rem; }
.stat-box-small .lbl { font-size: .75rem; color: #888; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; }

.action-btn { padding: .5rem 1.2rem; border-radius: 8px; font-size: .85rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; }
.btn-approve { background: #d4edda; color: #155724; }
.btn-reject { background: #f8d7da; color: #721c24; }
.btn-suspend { background: #fee2e2; color: #991b1b; }
.btn-reinstate { background: #e0f2fe; color: #0369a1; }
</style>

<div class="mb-4">
    <a href="/admin/vendors" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left me-2"></i>Back to Vendors</a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-title">Vendor Information</div>
                <div>
                    @php
                        $statusClass = 'bg-secondary';
                        $status = $vendor->vendor_status ?? 'pending';
                        if($status == 'approved') $statusClass = 'bg-success';
                        if($status == 'pending') $statusClass = 'bg-warning text-dark';
                        if($status == 'rejected' || $status == 'suspended') $statusClass = 'bg-danger';
                    @endphp
                    <span class="badge {{ $statusClass }} px-3 py-2" style="font-size: .8rem;">{{ ucfirst($status) }}</span>
                </div>
            </div>
            <div class="detail-body">
                @if(isset($vendor->vendorProfile) && $vendor->vendorProfile->logo)
                    <div class="mb-4">
                        <img src="{{ asset('storage/'.$vendor->vendorProfile->logo) }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                    </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Name</div>
                    <div class="info-val">{{ $vendor->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-val">{{ $vendor->email ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-val">{{ $vendor->phone ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Business Name</div>
                    <div class="info-val">{{ $vendor->vendorProfile->business_name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Bio</div>
                    <div class="info-val">{{ $vendor->vendorProfile->bio ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Website</div>
                    <div class="info-val">
                        @if(isset($vendor->vendorProfile->website) && $vendor->vendorProfile->website)
                            <a href="{{ $vendor->vendorProfile->website }}" target="_blank">{{ $vendor->vendorProfile->website }}</a>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address</div>
                    <div class="info-val">
                        {{ $vendor->vendorProfile->address ?? '' }}<br>
                        {{ $vendor->vendorProfile->city ?? '' }}, {{ $vendor->vendorProfile->postcode ?? '' }}<br>
                        {{ $vendor->vendorProfile->country ?? '' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">VAT Number</div>
                    <div class="info-val">{{ $vendor->vendorProfile->vat_number ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Bank Account</div>
                    <div class="info-val">{{ $vendor->vendorProfile->bank_account ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Joined</div>
                    <div class="info-val">{{ isset($vendor->created_at) ? $vendor->created_at->format('M d, Y h:i A') : 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-title">Actions</div>
            </div>
            <div class="detail-body">
                <div class="d-flex gap-2">
                    @if($status == 'pending')
                        <form action="/admin/vendors/{{ $vendor->id }}/approve" method="POST">
                            @csrf
                            <button type="submit" class="action-btn btn-approve"><i class="fa-solid fa-check"></i> Approve Vendor</button>
                        </form>
                        <button type="button" class="action-btn btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="fa-solid fa-times"></i> Reject Vendor</button>
                    @elseif($status == 'approved')
                        <form action="/admin/vendors/{{ $vendor->id }}/suspend" method="POST" onsubmit="return confirm('Are you sure you want to suspend this vendor?');">
                            @csrf
                            <button type="submit" class="action-btn btn-suspend"><i class="fa-solid fa-ban"></i> Suspend Vendor</button>
                        </form>
                    @elseif($status == 'suspended' || $status == 'rejected')
                        <form action="/admin/vendors/{{ $vendor->id }}/approve" method="POST">
                            @csrf
                            <button type="submit" class="action-btn btn-reinstate"><i class="fa-solid fa-rotate-left"></i> Reinstate Vendor</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-title">Vendor Stats</div>
            </div>
            <div class="detail-body">
                <div class="d-grid gap-3">
                    <div class="stat-box-small">
                        <div class="num">{{ $productCount ?? 0 }}</div>
                        <div class="lbl">Products</div>
                    </div>
                    <div class="stat-box-small">
                        <div class="num">{{ $rentalCount ?? 0 }}</div>
                        <div class="lbl">Rentals</div>
                    </div>
                    <div class="stat-box-small">
                        <div class="num">{{ $auctionCount ?? 0 }}</div>
                        <div class="lbl">Auctions</div>
                    </div>
                    <div class="stat-box-small">
                        <div class="num">£{{ number_format($total_revenue ?? 0, 2) }}</div>
                        <div class="lbl">Total Revenue</div>
                    </div>
                    <div class="stat-box-small">
                        <div class="num">{{ number_format($avg_rating ?? 0, 1) }}</div>
                        <div class="lbl">Avg Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/vendors/{{ $vendor->id ?? 0 }}/reject" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Reject Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required minlength="10" placeholder="Please provide a reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', $product->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $product->title }}</h1>
        <p class="page-subtitle">Submitted by <strong>{{ $product->seller->name ?? 'Unknown' }}</strong>
            on {{ $product->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Listings
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Action bar for pending listings --}}
@if($product->isPending())
<div class="action-bar">
    <form action="{{ route('admin.products.approve', $product) }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Approve Listing</button>
    </form>
    <button type="button" class="btn btn-danger" onclick="showRejectModal()">
        <i class="fas fa-times-circle"></i> Reject Listing
    </button>
</div>
@elseif($product->isApproved())
<div class="action-bar action-bar-approved">
    <i class="fas fa-check-circle"></i>
    Approved on <strong>{{ $product->approved_at?->format('d M Y') }}</strong>
    @if($product->approvedBy) by <strong>{{ $product->approvedBy->name }}</strong> @endif
    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" style="margin-left:auto">
        @csrf
        <button type="submit" class="btn btn-sm {{ $product->is_featured ? 'btn-warning' : 'btn-outline' }}">
            <i class="fas fa-star"></i> {{ $product->is_featured ? 'Remove Featured' : 'Mark Featured' }}
        </button>
    </form>
</div>
@elseif($product->isRejected())
<div class="action-bar action-bar-rejected">
    <i class="fas fa-times-circle"></i>
    <div>
        <strong>Rejected</strong>
        @if($product->rejection_reason)
            <p class="m0">{{ $product->rejection_reason }}</p>
        @endif
    </div>
</div>
@endif

<div class="detail-layout">

    {{-- Images --}}
    <div class="detail-images">
        @if($product->images && count($product->images))
        <img src="{{ asset('storage/' . $product->images[0]) }}" id="mainImg" class="main-img" alt="{{ $product->title }}">
        <div class="thumbs">
            @foreach($product->images as $img)
            <img src="{{ asset('storage/' . $img) }}" class="thumb {{ $loop->first ? 'active' : '' }}"
                 onclick="document.getElementById('mainImg').src=this.src; document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active')">
            @endforeach
        </div>
        @else
        <div class="no-img"><i class="fas fa-image fa-3x"></i><p>No images</p></div>
        @endif
    </div>

    {{-- Core info --}}
    <div class="detail-info">
        <div class="info-card">
            <div class="badges-row">
                <span class="badge badge-type-{{ $product->listing_type }}">{{ ucfirst($product->listing_type) }}</span>
                <span class="badge badge-cond">{{ ucfirst($product->condition) }}</span>
                <span class="badge badge-status-{{ $product->status }}">{{ ucfirst($product->status) }}</span>
                @if($product->is_featured)<span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>@endif
            </div>

            <div class="price-block">
                @if($product->isForSale())
                    <span class="price-main">£{{ number_format($product->price, 2) }}</span>
                @elseif($product->isForRent())
                    <span class="price-main">£{{ number_format($product->rental_price_day, 2) }}<small>/day</small></span>
                    @if($product->rental_price_week)
                    <span class="price-alt">£{{ number_format($product->rental_price_week, 2) }} / week</span>
                    @endif
                    @if($product->deposit_amount)
                    <span class="price-alt">Deposit: £{{ number_format($product->deposit_amount, 2) }}</span>
                    @endif
                @elseif($product->isForAuction())
                    <span class="price-main">Reserve: £{{ number_format($product->reserve_price, 2) }}</span>
                @endif
            </div>

            <table class="info-table">
                <tr><th>Seller</th><td>{{ $product->seller->name ?? '—' }} ({{ $product->seller->email ?? '—' }})</td></tr>
                <tr><th>Category</th><td>{{ $product->category->name ?? '—' }}</td></tr>
                <tr><th>Brand</th><td>{{ $product->brand ?? '—' }}</td></tr>
                <tr><th>Model</th><td>{{ $product->model_number ?? '—' }}</td></tr>
                <tr><th>SKU</th><td>{{ $product->sku ?? '—' }}</td></tr>
                <tr><th>Year</th><td>{{ $product->year_manufactured ?? '—' }}</td></tr>
                <tr><th>Quantity</th><td>{{ $product->quantity }}</td></tr>
                <tr><th>Location</th><td>{{ $product->location ?? '—' }}</td></tr>
                <tr><th>Collection</th><td>{{ $product->offers_collection ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Shipping</th><td>{{ $product->offers_shipping ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Views</th><td>{{ $product->view_count }}</td></tr>
                <tr><th>Submitted</th><td>{{ $product->created_at->format('d M Y, H:i') }}</td></tr>
            </table>
        </div>

        <div class="info-card">
            <h3 class="section-heading">Description</h3>
            <div class="description">{!! nl2br(e($product->description)) !!}</div>
        </div>

        @if($product->short_description)
        <div class="info-card">
            <h3 class="section-heading">Short Description</h3>
            <p>{{ $product->short_description }}</p>
        </div>
        @endif
    </div>

</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeRejectModal()">
    <div class="modal-box">
        <h3 class="modal-title"><i class="fas fa-times-circle text-danger"></i> Reject Listing</h3>
        <p class="modal-subtitle">Rejecting: <em>{{ $product->title }}</em></p>
        <form action="{{ route('admin.products.reject', $product) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Reason for rejection <span class="req">*</span></label>
                <textarea name="rejection_reason" rows="4" class="form-control" required
                          placeholder="Explain what needs to be fixed..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;gap:1rem}
.action-bar{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-radius:.75rem;margin-bottom:1.5rem;background:#fff;border:1px solid #e5e7eb;flex-wrap:wrap}
.action-bar-approved{background:#f0fdf4;border-color:#bbf7d0;color:#166534}
.action-bar-rejected{background:#fef2f2;border-color:#fecaca;color:#991b1b}
.m0{margin:0}
.detail-layout{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start}
@media(max-width:900px){.detail-layout{grid-template-columns:1fr}}
.detail-images{background:#fff;border-radius:1rem;padding:1rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.main-img{width:100%;max-height:380px;object-fit:contain;border-radius:.5rem;border:1px solid #f3f4f6}
.thumbs{display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap}
.thumb{width:72px;height:72px;object-fit:cover;border-radius:.5rem;border:2px solid #e5e7eb;cursor:pointer;transition:border .15s}
.thumb.active{border-color:#1d4ed8}
.no-img{text-align:center;padding:3rem;color:#9ca3af}
.detail-info{display:flex;flex-direction:column;gap:1rem}
.info-card{background:#fff;border-radius:1rem;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.badges-row{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem}
.badge{display:inline-block;padding:.2rem .65rem;border-radius:999px;font-size:.75rem;font-weight:600}
.badge-type-sell{background:#dbeafe;color:#1e40af}
.badge-type-rent{background:#dcfce7;color:#166534}
.badge-type-auction{background:#ede9fe;color:#6d28d9}
.badge-cond{background:#f3f4f6;color:#374151}
.badge-featured{background:#fef9c3;color:#854d0e}
.badge-status-approved{background:#dcfce7;color:#166534}
.badge-status-pending{background:#fef9c3;color:#854d0e}
.badge-status-rejected{background:#fee2e2;color:#991b1b}
.badge-status-draft{background:#f3f4f6;color:#6b7280}
.price-block{margin-bottom:1rem}
.price-main{font-size:2rem;font-weight:800;color:#1d4ed8;display:block}
.price-main small{font-size:.9rem;font-weight:400;color:#6b7280}
.price-alt{display:block;font-size:.9rem;color:#6b7280;margin-top:.25rem}
.info-table{width:100%;border-collapse:collapse;font-size:.875rem}
.info-table th,.info-table td{padding:.4rem .5rem;border-bottom:1px solid #f3f4f6;text-align:left}
.info-table th{color:#6b7280;font-weight:600;width:35%}
.section-heading{font-size:1rem;font-weight:700;margin:0 0 .75rem}
.description{font-size:.9rem;line-height:1.7;color:#374151;white-space:pre-line}
.btn-success{background:#16a34a;color:#fff;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer;font-weight:600}
.btn-outline{border:1px solid #d1d5db;background:transparent;color:#374151;padding:.5rem 1rem;border-radius:.5rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem}
.btn-sm{padding:.35rem .75rem;font-size:.8rem}
.btn-warning{background:#d97706;color:#fff;padding:.35rem .75rem;border-radius:.375rem;border:none;cursor:pointer;font-size:.8rem}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex!important;align-items:center;justify-content:center;padding:1rem}
.modal-box{background:#fff;border-radius:1rem;padding:2rem;max-width:500px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.modal-title{font-size:1.25rem;font-weight:700;margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem}
.modal-subtitle{color:#6b7280;margin-bottom:1.25rem;font-size:.875rem}
.modal-actions{display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem}
.text-danger{color:#dc2626}
.req{color:#dc2626}
.form-label{display:block;font-weight:600;font-size:.875rem;margin-bottom:.35rem}
.form-control{width:100%;padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;box-sizing:border-box}
.form-group{margin-bottom:1rem}
</style>
@endpush

@push('scripts')
<script>
function showRejectModal() { document.getElementById('rejectModal').style.display = 'flex'; }
function closeRejectModal() { document.getElementById('rejectModal').style.display = 'none'; }
</script>
@endpush

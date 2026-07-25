@extends('layouts.vendor')

@section('title', $product->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $product->title }}</h1>
        <p class="page-subtitle">Listing preview — as submitted</p>
    </div>
    <div class="header-actions">
        @unless($product->isApproved())
        <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-secondary">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endunless
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

{{-- Status banner --}}
<div class="status-banner status-{{ $product->status }}">
    @switch($product->status)
        @case('pending')
            <i class="fas fa-clock"></i> Your listing is <strong>pending admin review</strong>. You'll be notified once approved.
            @break
        @case('approved')
            <i class="fas fa-check-circle"></i> This listing is <strong>live on the marketplace</strong>!
            @break
        @case('rejected')
            <i class="fas fa-times-circle"></i> This listing was <strong>rejected</strong>.
            @if($product->rejection_reason)
                <br><span class="rejection-detail">Reason: {{ $product->rejection_reason }}</span>
            @endif
            <br><a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-outline mt-2">Edit & Re-submit</a>
            @break
        @case('draft')
            <i class="fas fa-file-alt"></i> This listing is a <strong>draft</strong> and has not been submitted yet.
            @break
    @endswitch
</div>

<div class="product-detail-grid">

    {{-- Images --}}
    <div class="product-images">
        @if($product->images && count($product->images))
        <div class="main-image">
            <img src="{{ asset('storage/' . $product->images[0]) }}" id="mainImg" alt="{{ $product->title }}">
        </div>
        @if(count($product->images) > 1)
        <div class="thumbs-row">
            @foreach($product->images as $img)
            <img src="{{ asset('storage/' . $img) }}" class="thumb {{ $loop->first ? 'active' : '' }}"
                 onclick="document.getElementById('mainImg').src=this.src; document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active')">
            @endforeach
        </div>
        @endif
        @else
        <div class="no-image"><i class="fas fa-image"></i><p>No images uploaded</p></div>
        @endif
    </div>

    {{-- Details --}}
    <div class="product-details">
        <div class="detail-section">
            <div class="badges-row">
                <span class="badge badge-type">{{ ucfirst($product->listing_type) }}</span>
                <span class="badge badge-cond">{{ ucfirst($product->condition) }}</span>
                <span class="badge badge-status-{{ $product->status }}">{{ ucfirst($product->status) }}</span>
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
                <tr><th>Category</th><td>{{ $product->category->name ?? '—' }}</td></tr>
                <tr><th>Brand</th><td>{{ $product->brand ?? '—' }}</td></tr>
                <tr><th>Model</th><td>{{ $product->model_number ?? '—' }}</td></tr>
                <tr><th>SKU</th><td>{{ $product->sku ?? '—' }}</td></tr>
                <tr><th>Year</th><td>{{ $product->year_manufactured ?? '—' }}</td></tr>
                <tr><th>Quantity</th><td>{{ $product->quantity }}</td></tr>
                <tr><th>Location</th><td>{{ $product->location ?? '—' }}</td></tr>
                <tr><th>Collection</th><td>{{ $product->offers_collection ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Shipping</th><td>{{ $product->offers_shipping ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Submitted</th><td>{{ $product->created_at->format('d M Y') }}</td></tr>
                @if($product->approved_at)
                <tr><th>Approved</th><td>{{ $product->approved_at->format('d M Y') }}</td></tr>
                @endif
                <tr><th>Views</th><td>{{ $product->view_count }}</td></tr>
            </table>
        </div>

        <div class="detail-section">
            <h3>Description</h3>
            <div class="description-text">{!! nl2br(e($product->description)) !!}</div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;gap:1rem}
.header-actions{display:flex;gap:.5rem}
.status-banner{padding:1rem 1.25rem;border-radius:.75rem;margin-bottom:1.5rem;font-size:.9rem;line-height:1.6}
.status-pending{background:#fffbeb;color:#92400e;border:1px solid #fde68a}
.status-approved{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0}
.status-rejected{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.status-draft{background:#f9fafb;color:#374151;border:1px solid #e5e7eb}
.rejection-detail{font-style:italic}
.product-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start}
@media(max-width:900px){.product-detail-grid{grid-template-columns:1fr}}
.product-images{background:#fff;border-radius:1rem;padding:1rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.main-image img{width:100%;max-height:380px;object-fit:contain;border-radius:.5rem}
.thumbs-row{display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap}
.thumb{width:72px;height:72px;object-fit:cover;border-radius:.5rem;border:2px solid #e5e7eb;cursor:pointer;transition:border .15s}
.thumb.active{border-color:#1d4ed8}
.no-image{text-align:center;padding:3rem;color:#9ca3af}
.no-image i{font-size:3rem}
.product-details{display:flex;flex-direction:column;gap:1rem}
.detail-section{background:#fff;border-radius:1rem;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.badges-row{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem}
.badge{display:inline-block;padding:.2rem .65rem;border-radius:999px;font-size:.75rem;font-weight:600}
.badge-type{background:#dbeafe;color:#1e40af}
.badge-cond{background:#f3f4f6;color:#374151}
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
.description-text{font-size:.9rem;line-height:1.7;color:#374151;white-space:pre-line}
</style>
@endpush

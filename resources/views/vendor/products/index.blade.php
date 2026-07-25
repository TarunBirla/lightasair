@extends('layouts.vendor')

@section('title', 'My Listings')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Listings</h1>
        <p class="page-subtitle">Manage your sell, rent and auction listings</p>
    </div>
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Listing
    </a>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('vendor.products.index') }}" class="filter-form">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['draft','pending','approved','rejected','sold','inactive'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="type" class="form-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            @foreach(['sell','rent','auction'] as $t)
                <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- Products grid --}}
@if($products->isEmpty())
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-box-open"></i></div>
        <h3>No listings yet</h3>
        <p>Create your first listing to start selling or renting equipment.</p>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary mt-3">Create Listing</a>
    </div>
@else
<div class="products-grid">
    @foreach($products as $product)
    <div class="product-card">
        <div class="product-image">
            <img src="{{ $product->primaryImageUrl() }}" alt="{{ $product->title }}" loading="lazy">
            <span class="badge badge-type badge-{{ $product->listing_type }}">{{ ucfirst($product->listing_type) }}</span>
            <span class="badge badge-status badge-{{ $product->status }}">{{ ucfirst($product->status) }}</span>
        </div>
        <div class="product-info">
            <h3 class="product-title">{{ Str::limit($product->title, 55) }}</h3>
            <p class="product-meta">
                <span><i class="fas fa-tag"></i> {{ $product->category->name ?? 'Uncategorised' }}</span>
                <span><i class="fas fa-circle-notch"></i> {{ ucfirst($product->condition) }}</span>
            </p>
            <p class="product-price">
                @if($product->isForSale())
                    £{{ number_format($product->price, 2) }}
                @elseif($product->isForRent())
                    £{{ number_format($product->rental_price_day, 2) }}<small>/day</small>
                @elseif($product->isForAuction())
                    Reserve: £{{ number_format($product->reserve_price, 2) }}
                @endif
            </p>

            {{-- Status notice --}}
            @if($product->isRejected() && $product->rejection_reason)
            <div class="rejection-note">
                <i class="fas fa-exclamation-triangle"></i> {{ $product->rejection_reason }}
            </div>
            @endif

            <div class="product-actions">
                <a href="{{ route('vendor.products.show', $product) }}" class="btn btn-sm btn-outline">View</a>
                @unless($product->isApproved())
                    <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-secondary">Edit</a>
                @endunless
                <form action="{{ route('vendor.products.destroy', $product) }}" method="POST"
                      onsubmit="return confirm('Delete this listing?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">
    {{ $products->appends(request()->query())->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
.page-title{font-size:1.75rem;font-weight:700;margin:0}
.page-subtitle{color:#6b7280;margin:0}
.filter-bar{margin-bottom:1.5rem}
.filter-form{display:flex;gap:.75rem;flex-wrap:wrap}
.form-select{padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.875rem;background:#fff}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.5rem}
.product-card{background:#fff;border-radius:1rem;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:box-shadow .2s}
.product-card:hover{box-shadow:0 8px 24px rgba(0,0,0,.12)}
.product-image{position:relative;height:180px;overflow:hidden;background:#f3f4f6}
.product-image img{width:100%;height:100%;object-fit:cover}
.badge{position:absolute;padding:.2rem .6rem;border-radius:999px;font-size:.7rem;font-weight:600}
.badge-type{top:.6rem;left:.6rem;background:#1d4ed8;color:#fff}
.badge-status{top:.6rem;right:.6rem}
.badge-approved{background:#16a34a;color:#fff}
.badge-pending{background:#d97706;color:#fff}
.badge-rejected{background:#dc2626;color:#fff}
.badge-draft{background:#6b7280;color:#fff}
.badge-sold{background:#7c3aed;color:#fff}
.badge-inactive{background:#9ca3af;color:#fff}
.product-info{padding:1rem}
.product-title{font-size:1rem;font-weight:600;margin:0 0 .5rem}
.product-meta{display:flex;gap:1rem;font-size:.8rem;color:#6b7280;margin-bottom:.5rem}
.product-price{font-size:1.2rem;font-weight:700;color:#1d4ed8;margin-bottom:.75rem}
.product-price small{font-size:.7rem;font-weight:400;color:#6b7280}
.rejection-note{background:#fef2f2;color:#dc2626;padding:.5rem .75rem;border-radius:.5rem;font-size:.8rem;margin-bottom:.75rem}
.product-actions{display:flex;gap:.4rem;flex-wrap:wrap}
.btn-sm{padding:.3rem .65rem;font-size:.8rem}
.btn-outline{border:1px solid #d1d5db;background:transparent;border-radius:.375rem;cursor:pointer;text-decoration:none;color:#374151}
.empty-state{text-align:center;padding:4rem 2rem;color:#6b7280}
.empty-icon{font-size:3rem;margin-bottom:1rem}
.pagination-wrapper{margin-top:1.5rem}
</style>
@endpush

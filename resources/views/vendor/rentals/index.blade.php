@extends('layouts.vendor')
@section('title', 'My Rental Listings')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rental Listings</h1>
        <p class="page-subtitle">Equipment available for rent on the marketplace</p>
    </div>
    <a href="{{ route('vendor.rentals.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Rental Listing
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" class="filter-form">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['draft','pending','approved','rejected','inactive'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>
</div>

@if($listings->isEmpty())
<div class="empty-state">
    <div class="empty-icon"><i class="fas fa-calendar-alt"></i></div>
    <h3>No rental listings yet</h3>
    <p>Add equipment to rent out and start earning.</p>
    <a href="{{ route('vendor.rentals.create') }}" class="btn btn-primary mt-3">Create Listing</a>
</div>
@else
<div class="products-grid">
    @foreach($listings as $listing)
    <div class="product-card">
        <div class="product-image">
            <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" loading="lazy">
            <span class="badge badge-type-rent">Rent</span>
            <span class="badge badge-status badge-{{ $listing->status }}">{{ ucfirst($listing->status) }}</span>
        </div>
        <div class="product-info">
            <h3 class="product-title">{{ Str::limit($listing->title, 55) }}</h3>
            <p class="product-meta">
                <span><i class="fas fa-tag"></i> {{ $listing->category->name ?? 'Uncategorised' }}</span>
                <span><i class="fas fa-boxes"></i> {{ $listing->total_qty }} unit(s)</span>
            </p>
            <p class="product-price">
                £{{ number_format($listing->price_per_day, 2) }}<small>/day</small>
                @if($listing->price_per_week)
                <span class="price-week"> · £{{ number_format($listing->price_per_week, 2) }}/wk</span>
                @endif
            </p>
            @if($listing->deposit_amount > 0)
            <p class="deposit-note"><i class="fas fa-lock"></i> Deposit: £{{ number_format($listing->deposit_amount, 2) }}</p>
            @endif

            @if($listing->isRejected() && $listing->rejection_reason)
            <div class="rejection-note"><i class="fas fa-exclamation-triangle"></i> {{ $listing->rejection_reason }}</div>
            @endif

            <div class="product-actions">
                <a href="{{ route('vendor.rentals.show', $listing) }}" class="btn btn-sm btn-outline">View</a>
                @if($listing->isApproved())
                <a href="{{ route('vendor.rentals.calendar', $listing) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-calendar"></i> Calendar
                </a>
                @endif
                @unless($listing->isApproved())
                <a href="{{ route('vendor.rentals.edit', $listing) }}" class="btn btn-sm btn-secondary">Edit</a>
                @endunless
                <form action="{{ route('vendor.rentals.destroy', $listing) }}" method="POST"
                      onsubmit="return confirm('Delete this listing?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="pagination-wrapper">{{ $listings->appends(request()->query())->links() }}</div>
@endif
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
.page-title{font-size:1.75rem;font-weight:700;margin:0}
.page-subtitle{color:#6b7280;margin:0}
.filter-bar{margin-bottom:1.5rem}
.filter-form{display:flex;gap:.75rem}
.form-select{padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.875rem;background:#fff}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.5rem}
.product-card{background:#fff;border-radius:1rem;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:box-shadow .2s}
.product-card:hover{box-shadow:0 8px 24px rgba(0,0,0,.12)}
.product-image{position:relative;height:180px;overflow:hidden;background:#f3f4f6}
.product-image img{width:100%;height:100%;object-fit:cover}
.badge{position:absolute;padding:.2rem .6rem;border-radius:999px;font-size:.7rem;font-weight:600}
.badge-type-rent{top:.6rem;left:.6rem;background:#16a34a;color:#fff}
.badge-status{top:.6rem;right:.6rem}
.badge-approved{background:#16a34a;color:#fff}
.badge-pending{background:#d97706;color:#fff}
.badge-rejected{background:#dc2626;color:#fff}
.badge-draft{background:#6b7280;color:#fff}
.badge-inactive{background:#9ca3af;color:#fff}
.product-info{padding:1rem}
.product-title{font-size:1rem;font-weight:600;margin:0 0 .5rem}
.product-meta{display:flex;gap:1rem;font-size:.8rem;color:#6b7280;margin-bottom:.5rem}
.product-price{font-size:1.2rem;font-weight:700;color:#16a34a;margin-bottom:.25rem}
.product-price small{font-size:.7rem;font-weight:400;color:#6b7280}
.price-week{font-size:.8rem;font-weight:500;color:#6b7280}
.deposit-note{font-size:.8rem;color:#6b7280;margin-bottom:.75rem}
.rejection-note{background:#fef2f2;color:#dc2626;padding:.5rem .75rem;border-radius:.5rem;font-size:.8rem;margin-bottom:.75rem}
.product-actions{display:flex;gap:.4rem;flex-wrap:wrap}
.btn-sm{padding:.3rem .65rem;font-size:.8rem}
.btn-secondary{background:#f3f4f6;color:#374151;border:none;border-radius:.375rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem}
.btn-outline{border:1px solid #d1d5db;background:transparent;border-radius:.375rem;cursor:pointer;text-decoration:none;color:#374151}
.empty-state{text-align:center;padding:4rem 2rem;color:#6b7280}
.empty-icon{font-size:3rem;margin-bottom:1rem}
.pagination-wrapper{margin-top:1.5rem}
</style>
@endpush

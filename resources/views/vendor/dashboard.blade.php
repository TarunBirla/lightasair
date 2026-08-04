@extends('layouts.vendor')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Welcome back, {{ auth()->user()->name }}!</h3>
        <p class="text-muted mb-0">Here's what's happening with <strong>{{ auth()->user()->vendorProfile->business_name ?? 'your business' }}</strong> today.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/vendor/products/create" class="btn-brand">
            <i class="bi bi-box-seam"></i> New Product
        </a>
        <a href="/vendor/rentals/create" class="btn-brand">
            <i class="bi bi-calendar3"></i> New Rental
        </a>
        <a href="/vendor/auctions/create" class="btn-brand">
            <i class="bi bi-hammer"></i> New Auction
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Total Listings</div>
            <div class="stat-card-value">{{ $stats['total_listings'] ?? 0 }}</div>
            <div class="stat-card-sub">Across all types</div>
            <i class="bi bi-box-seam stat-card-icon"></i>
            <div class="stat-card-accent" style="background: var(--brand);"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Active Products</div>
            <div class="stat-card-value">{{ $stats['active_products'] ?? 0 }}</div>
            <div class="stat-card-sub">Available for sale</div>
            <i class="bi bi-tags stat-card-icon"></i>
            <div class="stat-card-accent" style="background: #198754;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Active Rentals</div>
            <div class="stat-card-value">{{ $stats['active_rentals'] ?? 0 }}</div>
            <div class="stat-card-sub">Available for rent</div>
            <i class="bi bi-calendar3 stat-card-icon"></i>
            <div class="stat-card-accent" style="background: #0d6efd;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Active Auctions</div>
            <div class="stat-card-value">{{ $stats['active_auctions'] ?? 0 }}</div>
            <div class="stat-card-sub">Live right now</div>
            <i class="bi bi-hammer stat-card-icon"></i>
            <div class="stat-card-accent" style="background: #dc3545;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Total Revenue</div>
            <div class="stat-card-value">£{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            <div class="stat-card-sub">Lifetime earnings</div>
            <i class="bi bi-currency-pound stat-card-icon"></i>
            <div class="stat-card-accent" style="background: #20c997;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-label">Avg Rating</div>
            <div class="stat-card-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</div>
            <div class="stat-card-sub">Based on reviews</div>
            <i class="bi bi-star stat-card-icon"></i>
            <div class="stat-card-accent" style="background: #fd7e14;"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <div class="content-card-title">Recent Listings</div>
    </div>
    <div class="content-card-body p-0">
        <div class="table-responsive">
            <table class="vendor-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentListings ?? [] as $listing)
                    <tr>
                        <td><strong>{{ $listing->title ?? 'Untitled' }}</strong></td>
                        <td>{{ class_basename($listing) }}</td>
                        <td>
                            @php
                                $statusClass = 'bg-secondary';
                                if($listing->status == 'active' || $listing->status == 'approved') $statusClass = 'badge-approved';
                                if($listing->status == 'pending') $statusClass = 'badge-pending';
                                if($listing->status == 'rejected' || $listing->status == 'suspended') $statusClass = 'badge-rejected';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($listing->status ?? 'Unknown') }}</span>
                        </td>
                        <td>{{ $listing->created_at ? $listing->created_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No recent listings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

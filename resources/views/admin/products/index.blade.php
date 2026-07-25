@extends('layouts.admin')

@section('title', 'Marketplace Listings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Marketplace Listings</h1>
    <div class="stats-row">
        <div class="stat-pill stat-pending"><i class="fas fa-clock"></i> {{ $stats['pending'] }} Pending</div>
        <div class="stat-pill stat-approved"><i class="fas fa-check"></i> {{ $stats['approved'] }} Live</div>
        <div class="stat-pill stat-total"><i class="fas fa-list"></i> {{ $stats['total'] }} Total</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" class="filter-form">
        <input type="text" name="search" placeholder="Search title..." class="form-control" value="{{ request('search') }}">
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['draft','pending','approved','rejected','sold','inactive'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="type" class="form-control" onchange="this.form.submit()">
            <option value="">All Types</option>
            @foreach(['sell','rent','auction'] as $t)
                <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title / Seller</th>
                <th>Type</th>
                <th>Condition</th>
                <th>Price</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    <img src="{{ $product->primaryImageUrl() }}" alt="" class="table-thumb">
                </td>
                <td>
                    <div class="seller-info">
                        <strong>{{ Str::limit($product->title, 45) }}</strong>
                        <span class="seller-name">{{ $product->seller->name ?? 'Unknown' }}</span>
                        @if($product->brand) <span class="seller-name">{{ $product->brand }}</span> @endif
                    </div>
                </td>
                <td><span class="badge badge-type-{{ $product->listing_type }}">{{ ucfirst($product->listing_type) }}</span></td>
                <td>{{ ucfirst($product->condition) }}</td>
                <td>
                    @if($product->isForSale() && $product->price)
                        £{{ number_format($product->price, 2) }}
                    @elseif($product->isForRent() && $product->rental_price_day)
                        £{{ number_format($product->rental_price_day, 2) }}/day
                    @elseif($product->isForAuction() && $product->reserve_price)
                        £{{ number_format($product->reserve_price, 2) }} reserve
                    @else
                        —
                    @endif
                </td>
                <td><span class="badge badge-status-{{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                <td>{{ $product->created_at->format('d M Y') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-xs btn-outline" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($product->isPending())
                        <form action="{{ route('admin.products.approve', $product) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-success" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-xs btn-danger" title="Reject"
                                onclick="showRejectModal({{ $product->id }}, '{{ addslashes($product->title) }}')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                        <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-xs {{ $product->is_featured ? 'btn-warning' : 'btn-outline' }}" title="Toggle featured">
                                <i class="fas fa-star"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('Permanently delete this listing?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="empty-row">No listings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrapper">{{ $products->appends(request()->query())->links() }}</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeRejectModal()">
    <div class="modal-box">
        <h3 class="modal-title"><i class="fas fa-times-circle text-danger"></i> Reject Listing</h3>
        <p id="rejectModalProduct" class="modal-subtitle"></p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Reason for rejection <span class="req">*</span></label>
                <textarea name="rejection_reason" rows="4" class="form-control" required
                          placeholder="Please explain why this listing is being rejected..."></textarea>
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
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}
.stats-row{display:flex;gap:.5rem;flex-wrap:wrap}
.stat-pill{padding:.35rem .85rem;border-radius:999px;font-size:.8rem;font-weight:600;display:flex;align-items:center;gap:.4rem}
.stat-pending{background:#fef9c3;color:#854d0e}
.stat-approved{background:#dcfce7;color:#166534}
.stat-total{background:#dbeafe;color:#1e40af}
.filter-bar{margin-bottom:1.25rem}
.filter-form{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center}
.filter-form .form-control{max-width:180px}
.table-card{background:#fff;border-radius:1rem;overflow:auto;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.admin-table{width:100%;border-collapse:collapse;font-size:.875rem}
.admin-table th{background:#f9fafb;padding:.75rem 1rem;font-weight:600;color:#6b7280;text-transform:uppercase;font-size:.75rem;letter-spacing:.05em;border-bottom:1px solid #f3f4f6;white-space:nowrap}
.admin-table td{padding:.75rem 1rem;border-bottom:1px solid #f9fafb;vertical-align:middle}
.table-thumb{width:50px;height:50px;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
.seller-info{display:flex;flex-direction:column}
.seller-name{font-size:.75rem;color:#9ca3af}
.badge{display:inline-block;padding:.2rem .6rem;border-radius:999px;font-size:.72rem;font-weight:600;white-space:nowrap}
.badge-type-sell{background:#dbeafe;color:#1e40af}
.badge-type-rent{background:#dcfce7;color:#166534}
.badge-type-auction{background:#ede9fe;color:#6d28d9}
.badge-status-approved{background:#dcfce7;color:#166534}
.badge-status-pending{background:#fef9c3;color:#854d0e}
.badge-status-rejected{background:#fee2e2;color:#991b1b}
.badge-status-draft{background:#f3f4f6;color:#6b7280}
.badge-status-sold{background:#ede9fe;color:#6d28d9}
.action-btns{display:flex;gap:.3rem;flex-wrap:wrap}
.btn-xs{padding:.25rem .5rem;font-size:.75rem;border-radius:.375rem;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center}
.btn-success{background:#16a34a;color:#fff}
.btn-warning{background:#d97706;color:#fff}
.btn-outline{border:1px solid #d1d5db;background:transparent;color:#374151}
.btn-danger{background:#dc2626;color:#fff}
.empty-row{text-align:center;padding:2rem;color:#9ca3af}
.pagination-wrapper{margin-top:1rem}
/* Modal */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex!important;align-items:center;justify-content:center;padding:1rem}
.modal-box{background:#fff;border-radius:1rem;padding:2rem;max-width:500px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.modal-title{font-size:1.25rem;font-weight:700;margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem}
.modal-subtitle{color:#6b7280;margin-bottom:1.25rem;font-size:.875rem}
.modal-actions{display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem}
.text-danger{color:#dc2626}
.req{color:#dc2626}
.form-label{display:block;font-weight:600;font-size:.875rem;margin-bottom:.35rem}
.form-control{width:100%;padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;box-sizing:border-box;font-size:.9rem}
.btn-primary{background:#1d4ed8;color:#fff;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer}
.btn-sm{padding:.4rem .8rem;font-size:.875rem}
.form-group{margin-bottom:1rem}
</style>
@endpush

@push('scripts')
<script>
function showRejectModal(id, title) {
    document.getElementById('rejectModalProduct').textContent = '"' + title + '"';
    document.getElementById('rejectForm').action = '/admin/products/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endpush

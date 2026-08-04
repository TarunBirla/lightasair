@extends('layouts.admin')

@section('page-title', 'Listing Details — '.$product->title)
@section('breadcrumb', 'Admin / Marketplace / Listing #'.$product->id)

@section('content')

<style>
.action-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.4rem; border-radius: 12px; background: #fff; border: 1px solid #E8E6DF; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.action-bar-pending { background: #FFF9E6; border-color: #FFE082; }
.action-bar-approved { background: #EDFAF0; border-color: #A3E6B4; color: #1a7a3a; }
.action-bar-rejected { background: #FDE8E8; border-color: #F8B4B4; color: #9b1c1c; }

.detail-grid { display: grid; grid-template-columns: 1fr 400px; gap: 1.5rem; align-items: start; }
@media(max-width:992px){ .detail-grid { grid-template-columns: 1fr; } }

.info-card { background: #fff; border-radius: 12px; padding: 1.4rem; border: 1px solid #E8E6DF; margin-bottom: 1.5rem; }
.info-card-title { font-size: 1rem; font-weight: 800; color: #111; margin-bottom: 1.1rem; display: flex; align-items: center; gap: .5rem; }

.spec-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
.spec-table th, .spec-table td { padding: .55rem .4rem; border-bottom: 1px solid #f5f4f0; text-align: left; }
.spec-table th { color: #888; font-weight: 700; width: 40%; font-size: .78rem; text-transform: uppercase; }
.spec-table td { font-weight: 600; color: #111; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 450px; max-width: 95vw; }
</style>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success mb-3" style="border-radius:10px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3" style="border-radius:10px;">{{ session('error') }}</div>
@endif

{{-- Header bar --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;margin:0;">{{ $product->title }}</h2>
        <div style="font-size:.85rem;color:#888;margin-top:.2rem;">
            Submitted by <strong>{{ $product->seller->name ?? 'Unknown' }}</strong> ({{ $product->seller->email ?? '—' }}) on {{ $product->created_at->format('d M Y, H:i') }}
        </div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Listings
    </a>
</div>

{{-- Action status banner --}}
@if($product->isPending())
    <div class="action-bar action-bar-pending">
        <div class="d-flex align-items-center gap-2" style="font-size:.9rem;font-weight:700;color:#854d0e;">
            <i class="fa-solid fa-clock fa-lg"></i> This listing is pending your review before going live.
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-success" style="border-radius:8px;font-weight:800;font-size:.85rem;">
                    <i class="fa-solid fa-check me-1"></i> Approve Listing
                </button>
            </form>
            <button type="button" class="btn btn-danger" onclick="openReject()" style="border-radius:8px;font-weight:800;font-size:.85rem;">
                <i class="fa-solid fa-times me-1"></i> Reject Listing
            </button>
        </div>
    </div>
@elseif($product->isApproved())
    <div class="action-bar action-bar-approved">
        <div class="d-flex align-items-center gap-2" style="font-size:.9rem;font-weight:700;">
            <i class="fa-solid fa-circle-check fa-lg"></i> Approved on {{ $product->approved_at?->format('d M Y, H:i') }}
            @if($product->approvedBy) by {{ $product->approvedBy->name }} @endif
        </div>
        <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm {{ $product->is_featured ? 'btn-warning' : 'btn-outline-dark' }}" style="border-radius:8px;font-weight:700;">
                <i class="fa-solid fa-star me-1"></i> {{ $product->is_featured ? 'Featured (Click to Remove)' : 'Mark as Featured' }}
            </button>
        </form>
    </div>
@elseif($product->isRejected())
    <div class="action-bar action-bar-rejected">
        <div class="d-flex align-items-center gap-2" style="font-size:.9rem;font-weight:700;">
            <i class="fa-solid fa-circle-xmark fa-lg"></i> Rejected: {{ $product->rejection_reason ?? 'No reason specified' }}
        </div>
    </div>
@endif

<div class="detail-grid">
    {{-- LEFT COLUMN: GALLERY & DESCRIPTION --}}
    <div>
        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-images text-warning"></i> Product Photos</h4>
            @php $imageUrls = $product->allImageUrls(); @endphp
            <div style="border-radius:12px;overflow:hidden;background:#f5f4ef;border:1px solid #E8E6DF;margin-bottom:1rem;">
                <img id="mainAdminImg" src="{{ $product->primaryImageUrl() }}" alt="{{ $product->title }}" style="width:100%;height:360px;object-fit:contain;">
            </div>
            @if(count($imageUrls) > 1)
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    @foreach($imageUrls as $url)
                        <img src="{{ $url }}" onclick="document.getElementById('mainAdminImg').src='{{ $url }}'" style="width:75px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #ddd;">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-file-text text-warning"></i> Item Description</h4>
            <div style="font-size:.92rem;line-height:1.75;color:#374151;white-space:pre-line;">{{ $product->description }}</div>
        </div>

        @if($product->short_description)
            <div class="info-card">
                <h4 class="info-card-title"><i class="fa-solid fa-align-left text-warning"></i> Short Summary</h4>
                <p style="font-size:.9rem;color:#555;margin:0;">{{ $product->short_description }}</p>
            </div>
        @endif
    </div>

    {{-- RIGHT COLUMN: SPECIFICATIONS & SELLER INFO --}}
    <div>
        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-pound-sign text-warning"></i> Pricing & Type</h4>
            <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:1rem;margin-bottom:1rem;">
                <div style="font-size:.72rem;font-weight:700;color:#8d6e63;text-transform:uppercase;">Price / Rate</div>
                <div style="font-size:1.6rem;font-weight:900;color:#111;">
                    @if($product->isForSale())
                        £{{ number_format($product->price, 2) }}
                    @elseif($product->isForRent())
                        £{{ number_format($product->rental_price_day, 2) }} <small style="font-size:.8rem;font-weight:600;color:#666;">/ day</small>
                    @elseif($product->isForAuction())
                        Reserve: £{{ number_format($product->reserve_price, 2) }}
                    @endif
                </div>
            </div>

            <table class="spec-table">
                <tr><th>Listing Type</th><td><span class="badge bg-dark" style="font-size:.75rem;">{{ ucfirst($product->listing_type) }}</span></td></tr>
                <tr><th>Condition</th><td>{{ ucfirst($product->condition) }}</td></tr>
                <tr><th>Stock Qty</th><td>{{ $product->quantity }} unit(s)</td></tr>
                <tr><th>Category</th><td>{{ $product->category->name ?? 'Uncategorised' }}</td></tr>
                <tr><th>Brand</th><td>{{ $product->brand ?? '—' }}</td></tr>
                <tr><th>Model</th><td>{{ $product->model_number ?? '—' }}</td></tr>
                <tr><th>SKU</th><td>{{ $product->sku ?? '—' }}</td></tr>
                <tr><th>Year</th><td>{{ $product->year_manufactured ?? '—' }}</td></tr>
                <tr><th>Location</th><td>{{ $product->location ?? '—' }}</td></tr>
                <tr><th>Collection</th><td>{{ $product->offers_collection ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Shipping</th><td>{{ $product->offers_shipping ? 'Yes' : 'No' }}</td></tr>
                <tr><th>Total Views</th><td>{{ $product->view_count }}</td></tr>
            </table>
        </div>

        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-store text-warning"></i> Vendor Information</h4>
            <table class="spec-table">
                <tr><th>Vendor Name</th><td>{{ $product->seller->name ?? 'Unknown' }}</td></tr>
                <tr><th>Email</th><td>{{ $product->seller->email ?? '—' }}</td></tr>
                <tr><th>Business</th><td>{{ $product->seller->vendorProfile->business_name ?? '—' }}</td></tr>
                <tr><th>Status</th><td><span class="badge bg-success">{{ ucfirst($product->seller->status ?? 'active') }}</span></td></tr>
            </table>
            <div class="mt-3 text-end">
                @if($product->seller)
                    <a href="/admin/vendors/{{ $product->seller->id }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-size:.8rem;font-weight:700;">
                        <i class="fa-solid fa-user me-1"></i> View Vendor Profile
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="adminRejectModal">
    <div class="reject-box">
        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:.5rem;color:#111;"><i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Reject Listing</h3>
        <p style="font-size:.85rem;color:#888;margin-bottom:1rem;">Rejecting listing: <em>{{ $product->title }}</em></p>
        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="rejection_reason" class="form-control" rows="4" required minlength="10" placeholder="Explain what needs to be corrected by the vendor..." style="border-radius:10px;font-size:.88rem;"></textarea>
            </div>
            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                <button type="button" onclick="closeReject()" class="btn btn-light" style="border-radius:8px;font-weight:600;font-size:.85rem;">Cancel</button>
                <button type="submit" class="btn btn-danger" style="border-radius:8px;font-weight:700;font-size:.85rem;">Reject Listing</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReject() { document.getElementById('adminRejectModal').classList.add('open'); }
function closeReject() { document.getElementById('adminRejectModal').classList.remove('open'); }
</script>

@endsection

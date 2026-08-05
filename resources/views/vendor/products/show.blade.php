@extends('layouts.vendor')

@section('page-title', 'Product Details — ' . $product->title)
@section('breadcrumb', 'Vendor / Marketplace / Listing #' . $product->id)

@section('content')

<style>
.status-banner { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.4rem; border-radius: 12px; background: #fff; border: 1px solid #E8E6DF; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.status-pending  { background: #FFF9E6; border-color: #FFE082; color: #854d0e; }
.status-approved { background: #EDFAF0; border-color: #A3E6B4; color: #1a7a3a; }
.status-rejected { background: #FDE8E8; border-color: #F8B4B4; color: #9b1c1c; }
.status-draft    { background: #f3f4f6; border-color: #e5e7eb; color: #374151; }

.detail-grid { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
@media(max-width:992px){ .detail-grid { grid-template-columns: 1fr; } }

.info-card { background: #fff; border-radius: 12px; padding: 1.4rem; border: 1px solid #E8E6DF; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,.02); }
.info-card-title { font-size: 1rem; font-weight: 800; color: #111; margin-bottom: 1.1rem; display: flex; align-items: center; gap: .5rem; }

.spec-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
.spec-table th, .spec-table td { padding: .55rem .4rem; border-bottom: 1px solid #f5f4f0; text-align: left; }
.spec-table th { color: #888; font-weight: 700; width: 40%; font-size: .78rem; text-transform: uppercase; }
.spec-table td { font-weight: 600; color: #111; }
</style>

{{-- Header bar --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;margin:0;color:#111;">{{ $product->title }}</h2>
        <div style="font-size:.85rem;color:#888;margin-top:.2rem;">
            Submitted on {{ $product->created_at->format('d M Y, H:i') }}
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-warning" style="border-radius:10px;font-weight:800;font-size:.85rem;background:var(--brand, #FFC700);border:none;color:#111;">
            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Listing
        </a>
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;font-size:.85rem;">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

{{-- Status Banner --}}
<div class="status-banner status-{{ $product->status }}">
    <div class="d-flex align-items-center gap-2" style="font-size:.9rem;font-weight:700;">
        @if($product->isApproved())
            <i class="fa-solid fa-circle-check fa-lg"></i> Live on Marketplace (Approved on {{ $product->approved_at?->format('d M Y') ?? 'N/A' }})
        @elseif($product->isPending())
            <i class="fa-solid fa-clock fa-lg"></i> Pending Admin Approval (Submitted for review)
        @elseif($product->isRejected())
            <i class="fa-solid fa-circle-xmark fa-lg"></i> Rejected: {{ $product->rejection_reason ?? 'Please update product details.' }}
        @else
            <i class="fa-solid fa-file-lines fa-lg"></i> Draft Listing
        @endif
    </div>
    @if($product->isRejected())
        <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-danger" style="border-radius:8px;font-weight:800;">Fix & Resubmit</a>
    @endif
</div>

<div class="detail-grid">
    {{-- LEFT COLUMN: GALLERY & DESCRIPTION --}}
    <div>
        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-images text-warning"></i> Listing Images</h4>
            @php $imageUrls = $product->allImageUrls(); @endphp
            <div style="border-radius:12px;overflow:hidden;background:#f5f4ef;border:1px solid #E8E6DF;margin-bottom:1rem;">
                <img id="mainVendorImg" src="{{ $product->primaryImageUrl() }}" alt="{{ $product->title }}" style="width:100%;height:360px;object-fit:contain;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' fill=\'%23eee\'><text x=\'200\' y=\'150\' font-family=\'sans-serif\' font-size=\'16\' fill=\'%23888\' text-anchor=\'middle\'>No Image</text></svg>'">
            </div>
            @if(count($imageUrls) > 1)
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    @foreach($imageUrls as $url)
                        <img src="{{ $url }}" onclick="document.getElementById('mainVendorImg').src='{{ $url }}'" style="width:75px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #ddd;">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-file-text text-warning"></i> Description</h4>
            <div style="font-size:.92rem;line-height:1.75;color:#374151;white-space:pre-line;">{{ $product->description }}</div>
        </div>

        @if($product->short_description)
            <div class="info-card">
                <h4 class="info-card-title"><i class="fa-solid fa-align-left text-warning"></i> Summary</h4>
                <p style="font-size:.9rem;color:#555;margin:0;">{{ $product->short_description }}</p>
            </div>
        @endif
    </div>

    {{-- RIGHT COLUMN: PRICING & SPECIFICATIONS --}}
    <div>
        <div class="info-card">
            <h4 class="info-card-title"><i class="fa-solid fa-tag text-warning"></i> Price & Details</h4>
            <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:1rem;margin-bottom:1rem;">
                <div style="font-size:.72rem;font-weight:700;color:#8d6e63;text-transform:uppercase;">Price / Rate</div>
                <div style="font-size:1.6rem;font-weight:900;color:#111;">
                    @if($product->isForSale())
                        £{{ number_format($product->price, 2) }}
                    @elseif($product->isForRent())
                        £{{ number_format($product->rental_price_day, 2) }} <small style="font-size:.8rem;color:#666;">/ day</small>
                    @elseif($product->isForAuction())
                        Reserve: £{{ number_format($product->reserve_price, 2) }}
                    @endif
                </div>
            </div>

            <table class="spec-table">
                <tr><th>Listing Type</th><td><span class="badge bg-dark">{{ ucfirst($product->listing_type) }}</span></td></tr>
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
    </div>
</div>

@endsection

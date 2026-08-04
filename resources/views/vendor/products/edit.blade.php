@extends('layouts.vendor')

@section('title', 'Edit Sell Listing')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;margin:0;">Edit Sell Listing</h1>
        <p style="color:#888;font-size:.9rem;margin:.2rem 0 0 0;">Update listing details, pricing, photos or stock</p>
    </div>
    <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i> Back to Listings
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:12px;">
        <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please check form errors:</strong>
        <ul class="mb-0 ps-3" style="font-size:.88rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('vendor.products.update', $product->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="listing_type" value="sell">

    {{-- CARD 1: BASIC INFORMATION --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-info-circle-fill me-2 text-warning"></i> Item Details</h4>
        </div>
        <div class="content-card-body">
            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Listing Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" value="{{ old('title', $product->title) }}" required style="border-radius:10px;font-weight:700;">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Category</label>
                    <select name="category_id" class="form-select" style="border-radius:10px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Condition <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select" required style="border-radius:10px;">
                        <option value="new" @selected(old('condition', $product->condition) === 'new')>New</option>
                        <option value="used" @selected(old('condition', $product->condition) === 'used')>Used</option>
                        <option value="refurbished" @selected(old('condition', $product->condition) === 'refurbished')>Refurbished</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" style="border-radius:10px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Model Number</label>
                    <input type="text" name="model_number" class="form-control" value="{{ old('model_number', $product->model_number) }}" style="border-radius:10px;">
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" style="border-radius:10px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Short Summary</label>
                <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}" style="border-radius:10px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Full Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="6" required style="border-radius:10px;">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- CARD 2: IMAGES MANAGEMENT --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-images me-2 text-warning"></i> Item Photos</h4>
        </div>
        <div class="content-card-body">
            @php
                $existingImages = is_string($product->images) ? json_decode($product->images, true) : ($product->images ?? []);
            @endphp

            @if(count($existingImages) > 0)
                <label class="form-label mb-2" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Current Uploaded Photos</label>
                <p style="font-size:.8rem;color:#888;margin-bottom:1rem;">Check the box on any photo you want to delete:</p>
                <div class="row g-3 mb-4">
                    @foreach($existingImages as $idx => $path)
                        @php
                            $url = str_starts_with($path, 'http') ? $path : (str_starts_with($path, 'storage/') ? asset($path) : asset('storage/'.$path));
                        @endphp
                        <div class="col-6 col-sm-4 col-md-3">
                            <div style="position:relative;border-radius:12px;overflow:hidden;border:2px solid #e5e4df;background:#f5f4ef;">
                                <img src="{{ $url }}" alt="Photo {{ $idx+1 }}" style="width:100%;height:140px;object-fit:cover;">
                                <div style="padding:.5rem;background:#fff;border-top:1px solid #eee;font-size:.78rem;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $path }}" id="rm_prod_img_{{ $idx }}">
                                        <label class="form-check-label text-danger font-weight-bold" for="rm_prod_img_{{ $idx }}" style="font-weight:700;cursor:pointer;">
                                            <i class="bi bi-trash me-1"></i> Remove
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mb-2">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Upload Additional Photos</label>
                <input type="file" name="new_images[]" id="editProductImagesInput" class="form-control" multiple accept="image/jpeg,image/png,image/webp" style="border-radius:10px;">
                <small class="text-muted mt-1 d-block">You can select multiple photos (JPEG, PNG, WebP · Max 4 MB each).</small>
            </div>
            <div id="editProductImagesPreview" class="row g-2 mt-2"></div>
        </div>
    </div>

    {{-- CARD 3: PRICING & INVENTORY --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-currency-pound me-2 text-warning"></i> Pricing & Inventory</h4>
        </div>
        <div class="content-card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Selling Price (£) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="price" class="form-control form-control-lg" value="{{ old('price', $product->price) }}" required style="border-radius:10px;font-weight:800;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control form-control-lg" value="{{ old('quantity', $product->quantity) }}" min="1" required style="border-radius:10px;font-weight:800;">
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $product->location) }}" style="border-radius:10px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Fulfillment Options</label>
                    <div class="pt-2 d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="offers_collection" value="1" id="editOffersCollection" @checked(old('offers_collection', $product->offers_collection))>
                            <label class="form-check-label font-weight-bold" for="editOffersCollection" style="font-size:.88rem;font-weight:700;">
                                Collection Available
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="offers_shipping" value="1" id="editOffersShipping" @checked(old('offers_shipping', $product->offers_shipping))>
                            <label class="form-check-label font-weight-bold" for="editOffersShipping" style="font-size:.88rem;font-weight:700;">
                                Delivery / Shipping Available
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SUBMIT BUTTONS --}}
    <div style="display:flex;justify-content:flex-end;gap:.75rem;margin-top:1.5rem;">
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius:12px;font-size:.95rem;font-weight:700;">Cancel</a>
        <button type="submit" class="btn-brand" style="border-radius:12px;padding:.8rem 2.2rem;font-size:.95rem;font-weight:800;display:inline-flex;align-items:center;gap:.5rem;">
            <i class="bi bi-check-lg"></i> Update & Resubmit Listing
        </button>
    </div>
</form>

<script>
document.getElementById('editProductImagesInput')?.addEventListener('change', function(e) {
    const container = document.getElementById('editProductImagesPreview');
    container.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';
                col.innerHTML = `<div style="position:relative;border-radius:10px;overflow:hidden;border:2px solid #e5e4df;background:#f5f4ef;"><img src="${evt.target.result}" style="width:100%;height:110px;object-fit:cover;"></div>`;
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection

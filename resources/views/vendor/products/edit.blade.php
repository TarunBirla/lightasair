@extends('layouts.vendor')

@section('title', 'Edit Listing')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Listing</h1>
        <p class="page-subtitle">Update your listing details. It will be re-submitted for approval.</p>
    </div>
    <a href="{{ route('vendor.products.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Listings
    </a>
</div>

<form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="listing-form">
    @csrf @method('PUT')

    <div class="form-grid">
        <div class="form-main">

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-info-circle"></i> Basic Information</h2>
                <div class="form-group">
                    <label class="form-label">Listing Title <span class="req">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $product->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-control"
                           value="{{ old('short_description', $product->short_description) }}" maxlength="500">
                </div>
                <div class="form-group">
                    <label class="form-label">Full Description <span class="req">*</span></label>
                    <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Existing Images --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-images"></i> Images</h2>

                @if($product->images && count($product->images))
                <p class="form-label">Current Images (check to remove)</p>
                <div class="existing-images">
                    @foreach($product->images as $img)
                    <div class="existing-thumb">
                        <img src="{{ asset('storage/' . $img) }}" alt="Product image">
                        <label class="remove-label">
                            <input type="checkbox" name="remove_images[]" value="{{ $img }}">
                            <span class="remove-icon"><i class="fas fa-times"></i></span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="image-dropzone" style="margin-top:.75rem">
                    <input type="file" name="new_images[]" multiple accept="image/*" id="newImages" class="dropzone-input">
                    <div class="dropzone-label">
                        <i class="fas fa-cloud-upload-alt fa-2x"></i>
                        <p>Add more images</p>
                        <small>JPEG, PNG, WebP · Max 4 MB each</small>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview-grid"></div>
            </div>

            {{-- Pricing --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-pound-sign"></i> Pricing</h2>
                <div class="row-2">
                    <div class="form-group" id="sellPriceGroup">
                        <label class="form-label">Sale Price (£)</label>
                        <input type="number" name="price" step="0.01" min="0" class="form-control"
                               value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="form-group" id="rentDayGroup" style="display:none">
                        <label class="form-label">Rental Price / Day (£)</label>
                        <input type="number" name="rental_price_day" step="0.01" min="0" class="form-control"
                               value="{{ old('rental_price_day', $product->rental_price_day) }}">
                    </div>
                    <div class="form-group" id="rentWeekGroup" style="display:none">
                        <label class="form-label">Rental Price / Week (£)</label>
                        <input type="number" name="rental_price_week" step="0.01" min="0" class="form-control"
                               value="{{ old('rental_price_week', $product->rental_price_week) }}">
                    </div>
                    <div class="form-group" id="depositGroup" style="display:none">
                        <label class="form-label">Deposit Amount (£)</label>
                        <input type="number" name="deposit_amount" step="0.01" min="0" class="form-control"
                               value="{{ old('deposit_amount', $product->deposit_amount) }}">
                    </div>
                    <div class="form-group" id="reserveGroup" style="display:none">
                        <label class="form-label">Reserve Price (£)</label>
                        <input type="number" name="reserve_price" step="0.01" min="0" class="form-control"
                               value="{{ old('reserve_price', $product->reserve_price) }}">
                    </div>
                </div>
            </div>

        </div>

        <div class="form-sidebar">
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-layer-group"></i> Listing Type</h2>
                <div class="type-selector" id="typeSelector">
                    @foreach(['sell' => ['Sell','fa-shopping-cart'], 'rent' => ['Rent','fa-calendar-alt'], 'auction' => ['Auction','fa-gavel']] as $val => [$label, $icon])
                    <label class="type-option {{ old('listing_type', $product->listing_type) === $val ? 'active' : '' }}">
                        <input type="radio" name="listing_type" value="{{ $val }}"
                               {{ old('listing_type', $product->listing_type) === $val ? 'checked' : '' }}>
                        <i class="fas {{ $icon }}"></i>
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-tags"></i> Details</h2>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Condition <span class="req">*</span></label>
                    <select name="condition" class="form-control" required>
                        @foreach(['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('condition', $product->condition) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" min="1" class="form-control"
                           value="{{ old('quantity', $product->quantity) }}" required>
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-microchip"></i> Technical Info</h2>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Model Number</label>
                    <input type="text" name="model_number" class="form-control" value="{{ old('model_number', $product->model_number) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Year Manufactured</label>
                    <input type="number" name="year_manufactured" class="form-control"
                           value="{{ old('year_manufactured', $product->year_manufactured) }}" min="1900" max="{{ date('Y') }}">
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-map-marker-alt"></i> Location & Delivery</h2>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $product->location) }}">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="offers_collection" value="1" {{ old('offers_collection', $product->offers_collection) ? 'checked' : '' }}>
                        <span>Offers collection</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="offers_shipping" value="1" {{ old('offers_shipping', $product->offers_shipping) ? 'checked' : '' }}>
                        <span>Offers shipping / delivery</span>
                    </label>
                </div>
            </div>

            <div class="form-card">
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-paper-plane"></i> Save & Re-submit
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem}
.listing-form{width:100%}
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start}
@media(max-width:900px){.form-grid{grid-template-columns:1fr}}
.form-card{background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);margin-bottom:1.25rem}
.card-title{font-size:1rem;font-weight:700;margin:0 0 1.25rem;color:#111;display:flex;align-items:center;gap:.5rem}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:.875rem;font-weight:600;margin-bottom:.35rem;color:#374151}
.req{color:#dc2626}
.form-control{width:100%;padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.9rem;box-sizing:border-box;transition:border .15s}
.form-control:focus{outline:none;border-color:#1d4ed8;box-shadow:0 0 0 3px rgba(29,78,216,.1)}
.form-control.is-invalid{border-color:#dc2626}
.invalid-feedback{color:#dc2626;font-size:.8rem;margin-top:.25rem}
.d-block{display:block}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.type-selector{display:flex;flex-direction:column;gap:.5rem}
.type-option{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border:2px solid #e5e7eb;border-radius:.75rem;cursor:pointer;font-weight:600;transition:all .15s}
.type-option input{display:none}
.type-option.active,.type-option:has(input:checked){border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
.image-dropzone{position:relative;border:2px dashed #d1d5db;border-radius:.75rem;padding:1.5rem;text-align:center;cursor:pointer;transition:border .15s}
.image-dropzone:hover{border-color:#1d4ed8;background:#f8faff}
.dropzone-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.dropzone-label i{color:#9ca3af}
.dropzone-label p{margin:.5rem 0 .25rem;font-weight:600}
.dropzone-label small{color:#9ca3af}
.existing-images{display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:.5rem;margin-bottom:.75rem}
.existing-thumb{position:relative}
.existing-thumb img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
.remove-label{position:absolute;top:2px;right:2px;cursor:pointer}
.remove-label input{display:none}
.remove-icon{display:flex;align-items:center;justify-content:center;width:20px;height:20px;background:rgba(220,38,38,.85);color:#fff;border-radius:50%;font-size:.65rem}
.existing-thumb:has(input:checked) img{opacity:.4}
.existing-thumb:has(input:checked) .remove-icon{background:#16a34a}
.image-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:.5rem;margin-top:.5rem}
.preview-thumb{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
.checkbox-label{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.9rem}
.btn-full{width:100%;justify-content:center}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('[name="listing_type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        togglePriceFields(radio.value);
        document.querySelectorAll('.type-option').forEach(o => o.classList.remove('active'));
        radio.closest('.type-option').classList.add('active');
    });
});

function togglePriceFields(type) {
    document.getElementById('sellPriceGroup').style.display  = type === 'sell'    ? '' : 'none';
    document.getElementById('rentDayGroup').style.display    = type === 'rent'    ? '' : 'none';
    document.getElementById('rentWeekGroup').style.display   = type === 'rent'    ? '' : 'none';
    document.getElementById('depositGroup').style.display    = type === 'rent'    ? '' : 'none';
    document.getElementById('reserveGroup').style.display    = type === 'auction' ? '' : 'none';
}

const current = document.querySelector('[name="listing_type"]:checked');
if (current) togglePriceFields(current.value);

document.getElementById('newImages').addEventListener('change', function () {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-thumb';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush

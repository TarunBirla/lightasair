@extends('layouts.vendor')

@section('title', 'Create Listing')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Create New Listing</h1>
        <p class="page-subtitle">Fill in the details below. Your listing will be reviewed before going live.</p>
    </div>
    <a href="{{ route('vendor.products.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Listings
    </a>
</div>

<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="listing-form">
    @csrf

    <div class="form-grid">

        {{-- LEFT COLUMN --}}
        <div class="form-main">

            {{-- Basic Info --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-info-circle"></i> Basic Information</h2>

                <div class="form-group">
                    <label for="title" class="form-label">Listing Title <span class="req">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" placeholder="e.g. ARRI SkyPanel S60-C LED — Excellent Condition" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="short_description" class="form-label">Short Description</label>
                    <input type="text" id="short_description" name="short_description"
                           class="form-control @error('short_description') is-invalid @enderror"
                           value="{{ old('short_description') }}" placeholder="One-line summary (shown in cards)" maxlength="500">
                    @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Full Description <span class="req">*</span></label>
                    <textarea id="description" name="description" rows="6"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe condition, accessories included, usage history...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Images --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-images"></i> Images <small>(up to 10)</small></h2>

                <div class="image-dropzone" id="imageDropzone">
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="dropzone-input">
                    <div class="dropzone-label">
                        <i class="fas fa-cloud-upload-alt fa-2x"></i>
                        <p>Click or drag images here</p>
                        <small>JPEG, PNG, WebP · Max 4 MB each</small>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview-grid"></div>
                @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            {{-- Pricing --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-pound-sign"></i> Pricing</h2>

                <div class="row-2">
                    <div class="form-group" id="sellPriceGroup">
                        <label class="form-label">Sale Price (£)</label>
                        <input type="number" name="price" step="0.01" min="0"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" placeholder="0.00">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" id="rentDayGroup" style="display:none">
                        <label class="form-label">Rental Price / Day (£)</label>
                        <input type="number" name="rental_price_day" step="0.01" min="0"
                               class="form-control @error('rental_price_day') is-invalid @enderror"
                               value="{{ old('rental_price_day') }}" placeholder="0.00">
                    </div>
                    <div class="form-group" id="rentWeekGroup" style="display:none">
                        <label class="form-label">Rental Price / Week (£)</label>
                        <input type="number" name="rental_price_week" step="0.01" min="0"
                               class="form-control @error('rental_price_week') is-invalid @enderror"
                               value="{{ old('rental_price_week') }}" placeholder="0.00">
                    </div>
                    <div class="form-group" id="depositGroup" style="display:none">
                        <label class="form-label">Deposit Amount (£)</label>
                        <input type="number" name="deposit_amount" step="0.01" min="0"
                               class="form-control" value="{{ old('deposit_amount') }}" placeholder="0.00">
                    </div>
                    <div class="form-group" id="reserveGroup" style="display:none">
                        <label class="form-label">Reserve Price (£)</label>
                        <input type="number" name="reserve_price" step="0.01" min="0"
                               class="form-control @error('reserve_price') is-invalid @enderror"
                               value="{{ old('reserve_price') }}" placeholder="0.00">
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (sidebar) --}}
        <div class="form-sidebar">

            {{-- Listing Type --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-layer-group"></i> Listing Type</h2>
                <div class="type-selector" id="typeSelector">
                    @foreach(['sell' => ['Sell','fa-shopping-cart'], 'rent' => ['Rent','fa-calendar-alt'], 'auction' => ['Auction','fa-gavel']] as $val => [$label, $icon])
                    <label class="type-option {{ old('listing_type', 'sell') === $val ? 'active' : '' }}">
                        <input type="radio" name="listing_type" value="{{ $val }}"
                               {{ old('listing_type', 'sell') === $val ? 'checked' : '' }}>
                        <i class="fas {{ $icon }}"></i>
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @error('listing_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            {{-- Category & Condition --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-tags"></i> Details</h2>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Condition <span class="req">*</span></label>
                    <select name="condition" class="form-control @error('condition') is-invalid @enderror" required>
                        @foreach(['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('condition', 'used') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity <span class="req">*</span></label>
                    <input type="number" name="quantity" min="1" class="form-control"
                           value="{{ old('quantity', 1) }}" required>
                </div>
            </div>

            {{-- Technical Info --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-microchip"></i> Technical Info</h2>

                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="e.g. ARRI">
                </div>
                <div class="form-group">
                    <label class="form-label">Model Number</label>
                    <input type="text" name="model_number" class="form-control" value="{{ old('model_number') }}" placeholder="e.g. SkyPanel S60-C">
                </div>
                <div class="form-group">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Optional unique ID">
                </div>
                <div class="form-group">
                    <label class="form-label">Year Manufactured</label>
                    <input type="number" name="year_manufactured" class="form-control"
                           value="{{ old('year_manufactured') }}" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                </div>
            </div>

            {{-- Location & Delivery --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-map-marker-alt"></i> Location & Delivery</h2>

                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. London, UK">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="offers_collection" value="1" {{ old('offers_collection', true) ? 'checked' : '' }}>
                        <span>Offers collection</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="offers_shipping" value="1" {{ old('offers_shipping') ? 'checked' : '' }}>
                        <span>Offers shipping / delivery</span>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="form-card">
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-paper-plane"></i> Submit for Approval
                </button>
                <p class="submit-note">Your listing will be reviewed by our admin team before going live.</p>
            </div>

        </div>{{-- end sidebar --}}
    </div>{{-- end grid --}}
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
.card-title small{font-weight:400;color:#6b7280}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:.875rem;font-weight:600;margin-bottom:.35rem;color:#374151}
.req{color:#dc2626}
.form-control{width:100%;padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.9rem;transition:border .15s;box-sizing:border-box}
.form-control:focus{outline:none;border-color:#1d4ed8;box-shadow:0 0 0 3px rgba(29,78,216,.1)}
.form-control.is-invalid{border-color:#dc2626}
.invalid-feedback{color:#dc2626;font-size:.8rem;margin-top:.25rem}
.d-block{display:block}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
/* Type selector */
.type-selector{display:flex;flex-direction:column;gap:.5rem}
.type-option{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border:2px solid #e5e7eb;border-radius:.75rem;cursor:pointer;transition:all .15s;font-weight:600}
.type-option input{display:none}
.type-option i{font-size:1.1rem;width:1.25rem;color:#6b7280}
.type-option.active,.type-option:has(input:checked){border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
.type-option:has(input:checked) i{color:#1d4ed8}
/* Image dropzone */
.image-dropzone{position:relative;border:2px dashed #d1d5db;border-radius:.75rem;padding:2rem;text-align:center;cursor:pointer;transition:border .15s;margin-bottom:.75rem}
.image-dropzone:hover{border-color:#1d4ed8;background:#f8faff}
.dropzone-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.dropzone-label i{color:#9ca3af}
.dropzone-label p{margin:.5rem 0 .25rem;font-weight:600;color:#374151}
.dropzone-label small{color:#9ca3af}
.image-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:.5rem}
.preview-thumb{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
/* Checkbox */
.checkbox-label{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.9rem}
/* Submit */
.btn-full{width:100%;justify-content:center}
.submit-note{font-size:.75rem;color:#6b7280;text-align:center;margin-top:.75rem}
</style>
@endpush

@push('scripts')
<script>
// Type selector toggle — show/hide relevant price fields
document.querySelectorAll('[name="listing_type"]').forEach(radio => {
    radio.addEventListener('change', () => togglePriceFields(radio.value));
    document.querySelectorAll('.type-option').forEach(opt => {
        opt.querySelector('input').addEventListener('change', () => {
            document.querySelectorAll('.type-option').forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
        });
    });
});

function togglePriceFields(type) {
    document.getElementById('sellPriceGroup').style.display  = type === 'sell'    ? '' : 'none';
    document.getElementById('rentDayGroup').style.display    = type === 'rent'    ? '' : 'none';
    document.getElementById('rentWeekGroup').style.display   = type === 'rent'    ? '' : 'none';
    document.getElementById('depositGroup').style.display    = type === 'rent'    ? '' : 'none';
    document.getElementById('reserveGroup').style.display    = type === 'auction' ? '' : 'none';
}

// initialise from current checked value
const current = document.querySelector('[name="listing_type"]:checked');
if (current) togglePriceFields(current.value);

// Image preview
document.getElementById('images').addEventListener('change', function () {
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

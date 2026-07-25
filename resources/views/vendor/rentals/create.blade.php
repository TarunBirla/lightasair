@extends('layouts.vendor')
@section('title', 'Create Rental Listing')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Create Rental Listing</h1>
        <p class="page-subtitle">Add equipment to rent out. Your listing will be reviewed before going live.</p>
    </div>
    <a href="{{ route('vendor.rentals.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form action="{{ route('vendor.rentals.store') }}" method="POST" enctype="multipart/form-data" class="listing-form">
    @csrf

    <div class="form-grid">

        {{-- MAIN COLUMN --}}
        <div class="form-main">

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-info-circle"></i> Basic Information</h2>
                <div class="form-group">
                    <label class="form-label">Title <span class="req">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" placeholder="e.g. ARRI Orbiter 30cm — Available for Hire" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-control"
                           value="{{ old('short_description') }}" maxlength="500">
                </div>
                <div class="form-group">
                    <label class="form-label">Full Description <span class="req">*</span></label>
                    <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe the equipment, accessories included, condition notes...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-images"></i> Images <small>(up to 10)</small></h2>
                <div class="image-dropzone">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="dropzone-input">
                    <div class="dropzone-label">
                        <i class="fas fa-cloud-upload-alt fa-2x"></i>
                        <p>Click or drag images here</p>
                        <small>JPEG, PNG, WebP · Max 4 MB each</small>
                    </div>
                </div>
                <div id="imagePreview" class="image-preview-grid"></div>
            </div>

            {{-- Pricing --}}
            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-pound-sign"></i> Pricing & Deposit</h2>
                <div class="row-3">
                    <div class="form-group">
                        <label class="form-label">Price / Day (£) <span class="req">*</span></label>
                        <input type="number" name="price_per_day" step="0.01" min="0.01"
                               class="form-control @error('price_per_day') is-invalid @enderror"
                               value="{{ old('price_per_day') }}" placeholder="0.00" required>
                        @error('price_per_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price / Week (£)</label>
                        <input type="number" name="price_per_week" step="0.01" min="0"
                               class="form-control" value="{{ old('price_per_week') }}" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Security Deposit (£)</label>
                        <input type="number" name="deposit_amount" step="0.01" min="0"
                               class="form-control" value="{{ old('deposit_amount', 0) }}" placeholder="0.00">
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-group">
                        <label class="form-label">Min Rental Days <span class="req">*</span></label>
                        <input type="number" name="min_rental_days" min="1"
                               class="form-control" value="{{ old('min_rental_days', 1) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Rental Days</label>
                        <input type="number" name="max_rental_days" min="1"
                               class="form-control" value="{{ old('max_rental_days') }}" placeholder="Unlimited">
                    </div>
                </div>
            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="form-sidebar">

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-tags"></i> Category & Condition</h2>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">— Select —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Condition <span class="req">*</span></label>
                    <select name="condition" class="form-control" required>
                        @foreach(['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('condition','used') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Units Available <span class="req">*</span></label>
                    <input type="number" name="total_qty" min="1" class="form-control"
                           value="{{ old('total_qty', 1) }}" required>
                    <small class="help-text">How many identical units do you have?</small>
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-microchip"></i> Equipment Details</h2>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="e.g. ARRI">
                </div>
                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input type="text" name="model_number" class="form-control" value="{{ old('model_number') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <input type="number" name="year_manufactured" class="form-control"
                           value="{{ old('year_manufactured') }}" min="1900" max="{{ date('Y') }}">
                </div>
            </div>

            <div class="form-card">
                <h2 class="card-title"><i class="fas fa-map-marker-alt"></i> Location & Delivery</h2>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. London, UK">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="offers_delivery" value="1" {{ old('offers_delivery') ? 'checked' : '' }}
                               id="offersDelivery" onchange="document.getElementById('feeRow').style.display=this.checked?'':'none'">
                        <span>Offer delivery/collection service</span>
                    </label>
                </div>
                <div class="form-group" id="feeRow" style="{{ old('offers_delivery') ? '' : 'display:none' }}">
                    <label class="form-label">Delivery Fee (£)</label>
                    <input type="number" name="delivery_fee" step="0.01" min="0" class="form-control"
                           value="{{ old('delivery_fee', 0) }}" placeholder="0.00">
                </div>
            </div>

            <div class="form-card">
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-paper-plane"></i> Submit for Approval
                </button>
                <p class="submit-note">Your listing will be reviewed before going live.</p>
            </div>

        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem}
.listing-form{width:100%}
.form-grid{display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start}
@media(max-width:900px){.form-grid{grid-template-columns:1fr}}
.form-card{background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);margin-bottom:1.25rem}
.card-title{font-size:1rem;font-weight:700;margin:0 0 1.25rem;color:#111;display:flex;align-items:center;gap:.5rem}
.card-title small{font-weight:400;color:#6b7280}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:.875rem;font-weight:600;margin-bottom:.35rem;color:#374151}
.req{color:#dc2626}
.form-control{width:100%;padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.9rem;box-sizing:border-box;transition:border .15s}
.form-control:focus{outline:none;border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.1)}
.form-control.is-invalid{border-color:#dc2626}
.invalid-feedback{color:#dc2626;font-size:.8rem;margin-top:.25rem}
.row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:700px){.row-3,.row-2{grid-template-columns:1fr}}
.image-dropzone{position:relative;border:2px dashed #d1d5db;border-radius:.75rem;padding:2rem;text-align:center;cursor:pointer;margin-bottom:.75rem}
.image-dropzone:hover{border-color:#16a34a;background:#f0fdf4}
.dropzone-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.dropzone-label p{margin:.5rem 0 .25rem;font-weight:600}
.dropzone-label small{color:#9ca3af}
.image-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:.5rem}
.preview-thumb{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
.checkbox-label{display:flex;align-items:center;gap:.5rem;cursor:pointer}
.help-text{font-size:.78rem;color:#9ca3af}
.btn-full{width:100%;justify-content:center}
.submit-note{font-size:.75rem;color:#6b7280;text-align:center;margin-top:.75rem}
</style>
@endpush

@push('scripts')
<script>
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

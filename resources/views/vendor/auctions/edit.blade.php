@extends('layouts.vendor')

@section('title', 'Edit Timed Auction')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;margin:0;">Edit Timed Auction</h1>
        <p style="color:#888;font-size:.9rem;margin:.2rem 0 0 0;">Update auction details, reserve price, dates, or photos</p>
    </div>
    <a href="{{ route('vendor.auctions.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i> Back to Auctions
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

@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('vendor.auctions.update', $auction->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- CARD 1: OVERVIEW --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-info-circle-fill me-2 text-warning"></i> Equipment Overview</h4>
        </div>
        <div class="content-card-body">
            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Auction Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg" value="{{ old('title', $auction->title) }}" required style="border-radius:10px;font-weight:700;">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Category</label>
                    <select name="category_id" class="form-select" style="border-radius:10px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $auction->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Condition <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select" required style="border-radius:10px;">
                        <option value="new" @selected(old('condition', $auction->condition) === 'new')>New</option>
                        <option value="used" @selected(old('condition', $auction->condition) === 'used')>Used</option>
                        <option value="refurbished" @selected(old('condition', $auction->condition) === 'refurbished')>Refurbished</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Short Summary</label>
                <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $auction->short_description) }}" style="border-radius:10px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Detailed Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="6" required style="border-radius:10px;">{{ old('description', $auction->description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- CARD 2: IMAGES MANAGEMENT --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-images me-2 text-warning"></i> Auction Photos</h4>
        </div>
        <div class="content-card-body">
            @php
                $existingImages = is_string($auction->images) ? json_decode($auction->images, true) : ($auction->images ?? []);
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
                                        <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $path }}" id="rm_auc_img_{{ $idx }}">
                                        <label class="form-check-label text-danger font-weight-bold" for="rm_auc_img_{{ $idx }}" style="font-weight:700;cursor:pointer;">
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
                <input type="file" name="new_images[]" id="editAuctionImagesInput" class="form-control" multiple accept="image/jpeg,image/png,image/webp" style="border-radius:10px;">
                <small class="text-muted mt-1 d-block">You can select multiple photos (JPEG, PNG, WebP · Max 4 MB each).</small>
            </div>
            <div id="editAuctionImagesPreview" class="row g-2 mt-2"></div>
        </div>
    </div>

    {{-- CARD 3: PARAMETERS --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h4 class="content-card-title"><i class="bi bi-hammer me-2 text-warning"></i> Auction Parameters & Pricing</h4>
        </div>
        <div class="content-card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Starting Bid (£) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="starting_bid" class="form-control form-control-lg" value="{{ old('starting_bid', $auction->starting_bid) }}" required style="border-radius:10px;font-weight:800;">
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Reserve Price (£) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="reserve_price" class="form-control form-control-lg" value="{{ old('reserve_price', $auction->reserve_price) }}" required style="border-radius:10px;font-weight:800;">
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Min Bid Increment (£) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="1" name="min_increment" class="form-control form-control-lg" value="{{ old('min_increment', $auction->min_increment) }}" required style="border-radius:10px;font-weight:800;">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Start Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', $auction->start_time ? $auction->start_time->format('Y-m-d\TH:i') : '') }}" required style="border-radius:10px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">End Time (Deadline) <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', $auction->end_time ? $auction->end_time->format('Y-m-d\TH:i') : '') }}" required style="border-radius:10px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#555;">Item Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $auction->location) }}" style="border-radius:10px;">
            </div>
        </div>
    </div>

    {{-- SUBMIT BUTTONS --}}
    <div style="display:flex;justify-content:flex-end;gap:.75rem;margin-top:1.5rem;">
        <a href="{{ route('vendor.auctions.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius:12px;font-size:.95rem;font-weight:700;">Cancel</a>
        <button type="submit" class="btn-brand" style="border-radius:12px;padding:.8rem 2.2rem;font-size:.95rem;font-weight:800;display:inline-flex;align-items:center;gap:.5rem;">
            <i class="bi bi-check-lg"></i> Update & Resubmit Auction
        </button>
    </div>
</form>

<script>
document.getElementById('editAuctionImagesInput')?.addEventListener('change', function(e) {
    const container = document.getElementById('editAuctionImagesPreview');
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

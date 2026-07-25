@extends('layouts.vendor')

@section('title', 'Edit Rental Listing')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">Edit Rental Listing</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Update your equipment listing details</p>
    </div>
    <a href="{{ route('vendor.rentals.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;">Back to Listings</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('vendor.rentals.update', $rental->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;box-shadow:0 2px 12px rgba(0,0,0,.04);">
        <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1.2rem;">Basic Information</h4>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Equipment Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $rental->title) }}" required style="border-radius:10px;">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Category</label>
                <select name="category_id" class="form-select" style="border-radius:10px;">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $rental->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Condition</label>
                <select name="condition" class="form-select" required style="border-radius:10px;">
                    <option value="new" @selected(old('condition', $rental->condition) === 'new')>New</option>
                    <option value="used" @selected(old('condition', $rental->condition) === 'used')>Used</option>
                    <option value="refurbished" @selected(old('condition', $rental->condition) === 'refurbished')>Refurbished</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ old('brand', $rental->brand) }}" style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Model Number</label>
                <input type="text" name="model_number" class="form-control" value="{{ old('model_number', $rental->model_number) }}" style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Year Manufactured</label>
                <input type="number" name="year_manufactured" class="form-control" value="{{ old('year_manufactured', $rental->year_manufactured) }}" style="border-radius:10px;">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Full Description</label>
            <textarea name="description" class="form-control" rows="5" required style="border-radius:10px;">{{ old('description', $rental->description) }}</textarea>
        </div>
    </div>

    <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;box-shadow:0 2px 12px rgba(0,0,0,.04);">
        <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1.2rem;">Pricing & Terms</h4>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Price Per Day (£)</label>
                <input type="number" step="0.01" name="price_per_day" class="form-control" value="{{ old('price_per_day', $rental->price_per_day) }}" required style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Price Per Week (£)</label>
                <input type="number" step="0.01" name="price_per_week" class="form-control" value="{{ old('price_per_week', $rental->price_per_week) }}" style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Security Deposit (£)</label>
                <input type="number" step="0.01" name="deposit_amount" class="form-control" value="{{ old('deposit_amount', $rental->deposit_amount) }}" style="border-radius:10px;">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Total Units Available</label>
                <input type="number" name="total_qty" class="form-control" value="{{ old('total_qty', $rental->total_qty) }}" min="1" required style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Min Rental Days</label>
                <input type="number" name="min_rental_days" class="form-control" value="{{ old('min_rental_days', $rental->min_rental_days) }}" min="1" required style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Max Rental Days</label>
                <input type="number" name="max_rental_days" class="form-control" value="{{ old('max_rental_days', $rental->max_rental_days) }}" style="border-radius:10px;">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $rental->location) }}" style="border-radius:10px;">
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;">
        <button type="submit" class="btn btn-brand btn-lg" style="border-radius:12px;padding:.8rem 2rem;font-weight:800;">
            Update & Resubmit Listing
        </button>
    </div>
</form>
@endsection

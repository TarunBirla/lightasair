@extends('layouts.vendor')

@section('title', 'Create Auction')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">Create Timed Auction</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">List equipment for competitive timed bidding</p>
    </div>
    <a href="{{ route('vendor.auctions.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;">Back to Auctions</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('vendor.auctions.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;">
        <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1.2rem;">Equipment Overview</h4>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Auction Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. ARRI SkyPanel S60-C LED Softlight Package" value="{{ old('title') }}" required style="border-radius:10px;">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Category</label>
                <select name="category_id" class="form-select" style="border-radius:10px;">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Condition</label>
                <select name="condition" class="form-select" required style="border-radius:10px;">
                    <option value="new" @selected(old('condition') === 'new')>New</option>
                    <option value="used" @selected(old('condition') === 'used' || !old('condition'))>Used</option>
                    <option value="refurbished" @selected(old('condition') === 'refurbished')>Refurbished</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Detailed Description</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Describe inclusions, state of equipment, cosmetic condition, serial numbers..." required style="border-radius:10px;">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Images (Up to 10)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*" style="border-radius:10px;">
        </div>
    </div>

    <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;">
        <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1.2rem;">Auction Parameters & Pricing</h4>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Starting Bid (£)</label>
                <input type="number" step="0.01" name="starting_bid" class="form-control" placeholder="10.00" value="{{ old('starting_bid', '10.00') }}" required style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Reserve Price (£)</label>
                <input type="number" step="0.01" name="reserve_price" class="form-control" placeholder="500.00" value="{{ old('reserve_price', '0.00') }}" required style="border-radius:10px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Min Bid Increment (£)</label>
                <input type="number" step="0.01" name="min_increment" class="form-control" placeholder="5.00" value="{{ old('min_increment', '5.00') }}" required style="border-radius:10px;">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time', date('Y-m-d\TH:i')) }}" required style="border-radius:10px;">
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">End Time (Deadline)</label>
                <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', date('Y-m-d\TH:i', strtotime('+7 days'))) }}" required style="border-radius:10px;">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8rem;font-weight:700;text-transform:uppercase;">Item Location</label>
            <input type="text" name="location" class="form-control" placeholder="e.g. London, UK" value="{{ old('location') }}" style="border-radius:10px;">
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;">
        <button type="submit" class="btn btn-brand btn-lg" style="border-radius:12px;padding:.8rem 2.5rem;font-weight:800;">
            Submit Auction for Approval
        </button>
    </div>
</form>
@endsection

@extends('layouts.vendor')
@section('title', 'My Profile')
@section('page_title', 'Vendor Profile')

@section('content')
<form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-md-8">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title">Business Information</div>
                </div>
                <div class="content-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $profile->business_name ?? '') }}" required>
                        @error('business_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bio / Description</label>
                        <textarea name="bio" class="form-control" rows="4">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        @error('bio') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $profile->website ?? '') }}">
                        @error('website') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title">Location</div>
                </div>
                <div class="content-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address ?? '') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $profile->city ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Postcode</label>
                            <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $profile->postcode ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $profile->country ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title">Logo</div>
                </div>
                <div class="content-card-body text-center">
                    @if(isset($profile) && $profile->logo)
                        <img src="{{ asset('storage/'.$profile->logo) }}" alt="Logo" class="img-thumbnail mb-3" style="max-height: 150px;">
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @error('logo') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-title">Financial Details</div>
                </div>
                <div class="content-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">VAT Number</label>
                        <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $profile->vat_number ?? '') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bank Account Details</label>
                        <textarea name="bank_account" class="form-control" rows="3">{{ old('bank_account', $profile->bank_account ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn-brand justify-content-center py-2" style="font-size: 1rem;">
                    <i class="bi bi-save"></i> Save Profile
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

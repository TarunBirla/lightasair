@extends('layouts.admin')

@section('page-title', 'Commission Settings')
@section('breadcrumb', 'Admin / Commissions & Finance')

@section('content')

<style>
.c-card { background: #fff; border-radius: 12px; border: 1px solid #E8E6DF; overflow: hidden; margin-bottom: 1.5rem; }
.c-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #E8E6DF; background: #fdfdfc; display: flex; align-items: center; justify-content: space-between; }
.c-title { font-weight: 800; font-size: 1.05rem; color: #111; display: flex; align-items: center; gap: .5rem; }
.c-body { padding: 1.5rem; }

.f-group { display: flex; flex-direction: column; gap: .4rem; }
.f-label { font-size: .78rem; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: .05em; }
.f-input, .f-select { padding: .6rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .88rem; background: #fff; outline: none; font-family: inherit; }
.f-input:focus, .f-select:focus { border-color: #FFC700; }
.btn-save { padding: .65rem 1.5rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 800; font-size: .88rem; cursor: pointer; display: inline-flex; align-items: center; gap: .4rem; }

.c-table { width: 100%; border-collapse: collapse; background: #fff; }
.c-table th { background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #888; padding: .8rem 1.2rem; text-align: left; border-bottom: 1px solid #E8E6DF; }
.c-table td { padding: .9rem 1.2rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.c-table tr:last-child td { border: none; }
.c-table tr:hover td { background: #fefef9; }

.btn-del { background: #fee2e2; color: #991b1b; border: none; padding: .4rem .7rem; border-radius: 6px; font-size: .78rem; cursor: pointer; font-weight: 700; }
.btn-upd { background: #EAF3FF; color: #1a5fb4; border: none; padding: .4rem .8rem; border-radius: 6px; font-size: .78rem; cursor: pointer; font-weight: 700; }

.alert-info-c { background: #FFF9E6; border: 1px solid #FFE082; border-radius: 12px; padding: 1rem 1.2rem; color: #854d0e; font-size: .9rem; display: flex; align-items: center; gap: .8rem; margin-bottom: 1.5rem; }
</style>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:12px;">
        <strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please check form errors:</strong>
        <ul class="mb-0 ps-3" style="font-size:.88rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="alert-info-c">
    <i class="fa-solid fa-circle-info fs-4"></i>
    <div>
        <strong>Commission Calculation Rules:</strong> Specific category commission rates will override the default rate. If no category rate is set, the system falls back to the default rate (10.00%).
    </div>
</div>

{{-- Add New Rate Card --}}
<div class="c-card">
    <div class="c-header">
        <div class="c-title"><i class="fa-solid fa-percent text-warning"></i> Add / Set Commission Rate</div>
    </div>
    <div class="c-body">
        <form action="{{ route('admin.commissions.store') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="f-group">
                        <label class="f-label">Category</label>
                        <select name="category_id" class="f-select">
                            <option value="">Default (All Categories)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="f-group">
                        <label class="f-label">Listing Type <span class="text-danger">*</span></label>
                        <select name="listing_type" class="f-select" required>
                            <option value="sell" @selected(old('listing_type') === 'sell')>For Sale</option>
                            <option value="rent" @selected(old('listing_type') === 'rent')>Rental</option>
                            <option value="auction" @selected(old('listing_type') === 'auction')>Auction</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="f-group">
                        <label class="f-label">Rate (%) <span class="text-danger">*</span></label>
                        <input type="number" name="rate" class="f-input" step="0.01" min="0" max="100" required placeholder="e.g. 10.00" value="{{ old('rate', '10.00') }}">
                    </div>
                </div>
                <div class="col-md-2 pb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" @checked(old('is_active', true))>
                        <label class="form-check-label fw-bold" for="isActiveCheck" style="font-size:.88rem;cursor:pointer;">
                            Active Rate
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-save w-100 justify-content-center">
                        <i class="fa-solid fa-plus"></i> Save Rate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Existing Commission Rates --}}
<div class="c-card">
    <div class="c-header">
        <div class="c-title"><i class="fa-solid fa-list-check text-warning"></i> Active Commission Rates</div>
    </div>
    <div class="table-responsive">
        <table class="c-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Listing Type</th>
                    <th>Commission Rate (%)</th>
                    <th>Status</th>
                    <th style="width:200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions as $commission)
                <tr>
                    <td>
                        @if($commission->category)
                            <strong style="color:#111;">{{ $commission->category->name }}</strong>
                        @else
                            <span class="badge bg-dark" style="font-size:.75rem;">Default (Global Fallback)</span>
                        @endif
                    </td>
                    <td>
                        @if($commission->listing_type === 'sell')
                            <span class="badge bg-primary">For Sale</span>
                        @elseif($commission->listing_type === 'rent')
                            <span class="badge bg-success">Rental</span>
                        @else
                            <span class="badge bg-purple" style="background:#7c3aed;color:#fff;">Auction</span>
                        @endif
                    </td>
                    <td>
                        <form id="update-form-{{ $commission->id }}" action="{{ route('admin.commissions.update', $commission->id) }}" method="POST" style="display:flex;align-items:center;gap:.5rem;">
                            @csrf
                            @method('PUT')
                            <input type="number" name="rate" value="{{ number_format($commission->rate, 2) }}" class="f-input" step="0.01" min="0" max="100" style="width:110px;padding:.35rem .6rem;font-weight:700;">
                            <span style="font-weight:800;">%</span>
                        </form>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" form="update-form-{{ $commission->id }}" name="is_active" value="1" @checked($commission->is_active) onchange="document.getElementById('update-form-{{ $commission->id }}').submit()">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button type="submit" form="update-form-{{ $commission->id }}" class="btn-upd">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save
                            </button>
                            <form action="{{ route('admin.commissions.destroy', $commission->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this commission rate?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-muted">No custom commission rates configured yet. Default rate (10.00%) will apply.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

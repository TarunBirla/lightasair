@extends('layouts.admin')

@section('page-title', 'Add New Item')
@section('breadcrumb', 'Admin / Items / Create')

@section('content')

    <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-header bg-white py-3" style="border-bottom:1px solid #E8E6DF;">
            <h5 class="m-0 fw-bold"><i class="fa-solid fa-plus-circle text-warning me-2"></i> Add New Product / Item</h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger mb-4" style="border-radius:10px;">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Product Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Professional Camera Package">
                    </div>

                    {{-- Product Type Selection (Checkboxes) --}}
                    <div class="col-md-12">
                        <div class="p-3 border rounded" style="background:#FAF9F5;border-color:#E8E6DF !important;">
                            <label class="form-label fw-bold d-block mb-2">Product Type Options * (Select at least one)</label>
                            <div class="d-flex gap-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_sell" value="1" id="typeSellCheck" @checked(old('is_sell', 1)) onchange="togglePriceFields()">
                                    <label class="form-check-label fw-bold text-success" for="typeSellCheck">
                                        <i class="fa-solid fa-cart-shopping me-1"></i> Available for Sale (Sell)
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_rental" value="1" id="typeRentalCheck" @checked(old('is_rental', 0)) onchange="togglePriceFields()">
                                    <label class="form-check-label fw-bold text-primary" for="typeRentalCheck">
                                        <i class="fa-solid fa-calendar-check me-1"></i> Available for Hire (Rental)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Price Fields --}}
                    <div class="col-md-6" id="sellingPriceWrap">
                        <label class="form-label fw-bold text-success">Selling Price (£) *</label>
                        <input type="number" step="0.01" name="selling_price" id="sellingPriceInput" class="form-control" value="{{ old('selling_price') }}" placeholder="0.00">
                    </div>

                    <div class="col-md-6" id="rentalPriceWrap">
                        <label class="form-label fw-bold text-primary">Rental Price (£/day) *</label>
                        <input type="number" step="0.01" name="rental_price" id="rentalPriceInput" class="form-control" value="{{ old('rental_price', old('price_per_day')) }}" placeholder="0.00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Stock Quantity *</label>
                        <input type="number" name="qty" class="form-control" value="{{ old('qty', 1) }}" min="1" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" @selected(old('status') == 'active')>Active</option>
                            <option value="inactive" @selected(old('status') == 'inactive')>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Upload Product Images</label>
                        <input type="file" name="image[]" multiple class="form-control">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold px-4" style="background:#FFC700;border:none;color:#111;">
                        <i class="fa-solid fa-check me-1"></i> Save Item
                    </button>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('#description')).catch(error => console.error(error));
            togglePriceFields();
        });

        function togglePriceFields() {
            const isSell = document.getElementById('typeSellCheck').checked;
            const isRental = document.getElementById('typeRentalCheck').checked;

            const sellingWrap = document.getElementById('sellingPriceWrap');
            const rentalWrap = document.getElementById('rentalPriceWrap');

            if (sellingWrap) sellingWrap.style.display = isSell ? 'block' : 'none';
            if (rentalWrap) rentalWrap.style.display = isRental ? 'block' : 'none';
        }
    </script>

@endsection
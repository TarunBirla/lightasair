@extends('layouts.admin')

@section('content')

    <div class="card">

        <div class="card-header">
            Add Item
        </div>

        <div class="card-body">

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Category</label>

                        <select name="category_id" class="form-control">

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Title</label>

                        <input type="text" name="title" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>

                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Qty</label>

                        <input type="number" name="qty" class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Price Per Day (£)</label>

                        <input type="number" step="0.01" name="price_per_day" class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Image</label>

                        <input type="file" name="image[]" multiple class="form-control">

                    </div>

                </div>

                <button class="btn btn-success">

                    Save Item

                </button>

            </form>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    ClassicEditor
                        .create(document.querySelector('#description'))
                        .catch(error => {
                            console.error(error);
                        });
                });
            </script>

        </div>

    </div>

@endsection
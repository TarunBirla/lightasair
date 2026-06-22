@extends('layouts.admin')

@section('content')

    <div class="card">

        <div class="card-header">
            Edit Item
        </div>

        <div class="card-body">

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Category</label>

                        <select name="category_id" class="form-control">

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Title</label>

                        <input type="text" name="title" value="{{ $item->title }}" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>

                        <textarea name="description" id="description"
                            class="form-control">{{ $item->description }}</textarea>
                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Total Qty</label>

                        <input type="number" name="qty" value="{{ $item->qty }}" class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Available Qty</label>

                        <input type="number" name="available_qty" value="{{ $item->available_qty }}" class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Price Per Day (£)</label>

                        <input type="number" step="0.01" name="price_per_day" value="{{ $item->price_per_day }}"
                            class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                   @php

$images = [];

if ($item->image) {

    if (is_array($item->image)) {

        $images = $item->image;

    } else {

        $decoded = json_decode($item->image, true);

        if (is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$item->image];
        }
    }
}

@endphp

                    <div class="row">

                        @foreach($images as $index => $img)

                                <div class="col-md-2 mb-3 image-box">

                                    <div style="position:relative">

                                        <img src="{{ asset('uploads/items/' . $img) }}" class="img-fluid border rounded"
                                            style="height:120px;width:100%;object-fit:cover;">

                                        <button type="button" class="btn btn-danger btn-sm remove-image" data-index="{{ $index }}"
                                            style="
                            position:absolute;
                            top:5px;
                            right:5px;
                            border-radius:50%;
                            width:28px;
                            height:28px;
                            padding:0;">
                                            ×
                                        </button>

                                    </div>

                                </div>

                        @endforeach

                    </div>

                    <input type="hidden" name="deleted_images" id="deleted_images">

                    <div class="col-md-12 mb-3">

                        <label>Add More Images</label>

                        <input type="file" name="image[]" multiple class="form-control">

                    </div>

                </div>

                <button class="btn btn-primary">

                    Update Item

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
               <script>

let deletedImages = [];

document.querySelectorAll('.remove-image').forEach(btn => {

    btn.addEventListener('click', function() {

        let index = this.dataset.index;

        deletedImages.push(index);

        document.getElementById('deleted_images').value =
            JSON.stringify(deletedImages);

        this.closest('.image-box').remove();

    });

});

</script>

        </div>

    </div>

@endsection
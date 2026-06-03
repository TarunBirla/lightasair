@extends('layouts.admin')

@section('content')

<div class="card">

    <div class="card-header">
        Edit Category
    </div>

    <div class="card-body">
        <div class="mb-3">

    <label>Existing Images</label>

    <div class="row">

        @foreach($category->images as $img)

        <div class="col-md-3 text-center">

            <img
                src="{{ asset('uploads/category/'.$img->image) }}"
                class="img-fluid border p-1 mb-2">

            <form action="{{ route('category.image.delete',$img->id) }}"
                  method="POST">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-danger btn-sm">
                    Delete
                </button>

            </form>

        </div>

        @endforeach

    </div>

</div>

        <form
            action="{{ route('category.update',$category->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    value="{{ $category->name }}"
                    class="form-control">

            </div>

           

            <div class="mb-3">

                <label>Add More Images</label>

                <input
                    type="file"
                    name="images[]"
                    multiple
                    class="form-control">

            </div>

            <div class="mb-3">

                <label>Status</label>

                <select
                    name="status"
                    class="form-control">

                    <option
                        value="active"
                        {{ $category->status=='active' ? 'selected' : '' }}>

                        Active

                    </option>

                    <option
                        value="inactive"
                        {{ $category->status=='inactive' ? 'selected' : '' }}>

                        Inactive

                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Update Category

            </button>

        </form>

    </div>

</div>

@endsection
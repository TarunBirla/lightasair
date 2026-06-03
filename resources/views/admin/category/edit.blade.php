@extends('layouts.admin')

@section('content')

<div class="card">

    <div class="card-header">
        Edit Category
    </div>

    <div class="card-body">

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

                <label>Existing Images</label>

                <div style="display:flex;gap:15px;flex-wrap:wrap;">

                    @foreach($category->images as $img)

                        <div style="text-align:center;">

                            <img
                                src="{{ asset('uploads/category/'.$img->image) }}"
                                width="120"
                                style="border:1px solid #ddd;padding:5px;border-radius:6px;">

                            <form
                                action="{{ route('category.image.delete',$img->id) }}"
                                method="POST"
                                style="margin-top:5px;">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete Image ?')">

                                    Delete

                                </button>

                            </form>

                        </div>

                    @endforeach

                </div>

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
@extends('layouts.admin')

@section('content')

<div class="card">

    <div class="card-header">
        Add Category
    </div>

    <div class="card-body">

        <form
        action="{{ route('category.store') }}"
        method="POST"
        enctype="multipart/form-data">

            @csrf

            <div class="mb-3">

                <label>
                    Category Name
                </label>

                <input
                type="text"
                name="name"
                class="form-control">

            </div>

            <div class="mb-3">

                <label>
                    Image
                </label>

                <input
                type="file"
                name="image"
                class="form-control">

            </div>

            <div class="mb-3">

                <label>
                    Status
                </label>

                <select
                name="status"
                class="form-control">

                    <option value="active">
                        Active
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                </select>

            </div>

            <button
            class="btn btn-success">

                Save Category

            </button>

        </form>

    </div>

</div>

@endsection
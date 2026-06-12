@extends('layouts.admin')

@section('content')

<div class="container">

<h4>Add Generator Banner</h4>

<form
    action="{{ route('generator-banners.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label>Title</label>

        <input
            type="text"
            name="title"
            class="form-control"
            required>
    </div>

    <div class="mb-3">
        <label>Banner Image</label>

        <input
            type="file"
            name="image"
            class="form-control"
            required>
    </div>

    <div class="mb-3">
        <label>Select Item</label>

        <select
            name="item_id"
            class="form-control">

            <option value="">
                Select Item
            </option>

            @foreach($items as $item)

            <option value="{{ $item->id }}">
                {{ $item->title }}
            </option>

            @endforeach

        </select>

    </div>

    <!-- <div class="mb-3">
        <label>Custom Link</label>

        <input
            type="text"
            name="link"
            class="form-control">
    </div> -->

    <div class="mb-3">
        <label>Status</label>

        <select
            name="status"
            class="form-control">

            <option value="1">
                Active
            </option>

            <option value="0">
                Inactive
            </option>

        </select>
    </div>

    <button class="btn btn-success">
        Save Banner
    </button>

</form>

</div>

@endsection
@extends('layouts.admin')

@section('content')

<div class="container">

<h4>Edit Generator Banner</h4>

<form
    action="{{ route('generator-banners.update',$banner->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Title</label>

        <input
            type="text"
            name="title"
            value="{{ $banner->title }}"
            class="form-control">
    </div>

    <div class="mb-3">

        <img
            src="{{ asset('uploads/generator-banner/'.$banner->image) }}"
            width="150">

    </div>

    <div class="mb-3">
        <label>Change Image</label>

        <input
            type="file"
            name="image"
            class="form-control">
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

            <option
                value="{{ $item->id }}"
                {{ $banner->item_id == $item->id ? 'selected' : '' }}>

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
            value="{{ $banner->link }}"
            class="form-control">
    </div> -->

    <div class="mb-3">
        <label>Status</label>

        <select
            name="status"
            class="form-control">

            <option
                value="1"
                {{ $banner->status == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option
                value="0"
                {{ $banner->status == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

    </div>

    <button class="btn btn-primary">
        Update Banner
    </button>

</form>

</div>

@endsection
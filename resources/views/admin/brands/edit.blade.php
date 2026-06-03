@extends('layouts.admin')

@section('page-title','Edit Brand')
@section('breadcrumb','Admin / Brands / Edit')

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('brands.update',$brand->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <img src="{{ asset('uploads/brands/'.$brand->image) }}"
             width="120"
             class="mb-2">

    </div>

    <div class="mb-3">

        <label class="form-label">
            Brand Title
        </label>

        <input type="text"
               name="title"
               value="{{ $brand->title }}"
               class="form-control">

    </div>

    <div class="mb-3">

        <label class="form-label">
            Brand Image
        </label>

        <input type="file"
               name="image"
               class="form-control">

    </div>

    <button class="btn btn-success">
        Update Brand
    </button>

</form>

</div>
</div>

@endsection
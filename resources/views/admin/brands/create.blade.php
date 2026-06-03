@extends('layouts.admin')

@section('page-title','Add Brand')
@section('breadcrumb','Admin / Brands / Create')

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('brands.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label class="form-label">
            Brand Title
        </label>

        <input type="text"
               name="title"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">
            Brand Image
        </label>

        <input type="file"
               name="image"
               class="form-control"
               required>
    </div>

    <button class="btn btn-success">
        Save Brand
    </button>

</form>

</div>
</div>

@endsection
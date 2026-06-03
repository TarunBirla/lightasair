@extends('layouts.admin')

@section('page-title','Edit Television')
@section('breadcrumb','Admin / Television / Edit')

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('televisions.update',$television->id) }}"
      method="POST"
      enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="mb-3">

<img src="{{ asset('uploads/televisions/'.$television->image) }}"
     width="150">

</div>

<div class="mb-3">

<label>Title</label>

<input type="text"
       name="title"
       value="{{ $television->title }}"
       class="form-control">

</div>

<div class="mb-3">

<label>Tag</label>

<input type="text"
       name="tag"
       value="{{ $television->tag }}"
       class="form-control">

</div>

<div class="mb-3">

<label>Description</label>

<textarea name="description"
          rows="5"
          class="form-control">{{ $television->description }}</textarea>

</div>

<div class="mb-3">

<label>Image</label>

<input type="file"
       name="image"
       class="form-control">

</div>

<button class="btn btn-success">
Update Television
</button>

</form>

</div>
</div>

@endsection
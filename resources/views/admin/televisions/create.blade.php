@extends('layouts.admin')

@section('content')

<form action="{{ route('televisions.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="card">
<div class="card-body">

<div class="mb-3">
<label>Title</label>
<input type="text"
       name="title"
       class="form-control">
</div>

<div class="mb-3">
<label>Tag</label>
<input type="text"
       name="tag"
       class="form-control">
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description"
          rows="5"
          class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Image</label>
<input type="file"
       name="image"
       class="form-control">
</div>

<button class="btn btn-success">
Save
</button>

</div>
</div>

</form>

@endsection
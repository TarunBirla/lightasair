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

<img
src="{{ asset('uploads/category/'.$category->image) }}"
width="120">

</div>

<div class="mb-3">

<label>Image</label>

<input
type="file"
name="image"
class="form-control">

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option
value="active"
{{ $category->status=='active' ? 'selected':'' }}>
Active
</option>

<option
value="inactive"
{{ $category->status=='inactive' ? 'selected':'' }}>
Inactive
</option>

</select>

</div>

<button
class="btn btn-primary">

Update Category

</button>

</form>

</div>

</div>

@endsection
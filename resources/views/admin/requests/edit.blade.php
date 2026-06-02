@extends('layouts.admin')

@section('content')

<div class="container">

<form
action="{{ route('banner.update',$banner->id) }}"
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
src="{{ asset('uploads/banner/'.$banner->image) }}"
width="150">

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
{{ $banner->status=='active' ? 'selected':'' }}>
Active
</option>

<option
value="inactive"
{{ $banner->status=='inactive' ? 'selected':'' }}>
Inactive
</option>

</select>

</div>

<button class="btn btn-primary">
Update
</button>

</form>

</div>

@endsection
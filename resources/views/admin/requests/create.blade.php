@extends('layouts.admin')

@section('content')

<div class="container">

<form
action="{{ route('banner.store') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<div class="mb-3">
<label>Title</label>

<input
type="text"
name="title"
class="form-control">
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

<option value="active">
Active
</option>

<option value="inactive">
Inactive
</option>

</select>
</div>

<button class="btn btn-success">
Save
</button>

</form>

</div>

@endsection
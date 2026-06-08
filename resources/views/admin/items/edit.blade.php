@extends('layouts.admin')

@section('content')

<div class="card">

<div class="card-header">
Edit Item
</div>

<div class="card-body">

<form
action="{{ route('items.update',$item->id) }}"
method="POST"
enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">

<label>Category</label>

<select
name="category_id"
class="form-control">

@foreach($categories as $category)

<option
value="{{ $category->id }}"
{{ $item->category_id == $category->id ? 'selected':'' }}>

{{ $category->name }}

</option>

@endforeach

</select>

</div>

<div class="col-md-6 mb-3">

<label>Title</label>

<input
type="text"
name="title"
value="{{ $item->title }}"
class="form-control">

</div>

<div class="col-md-12 mb-3">
    <label>Description</label>

    <textarea
        name="description"
        id="description"
        class="form-control">{{ $item->description }}</textarea>
</div>

<div class="col-md-3 mb-3">

<label>Total Qty</label>

<input
type="number"
name="qty"
value="{{ $item->qty }}"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Available Qty</label>

<input
type="number"
name="available_qty"
value="{{ $item->available_qty }}"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Price Per Day (£)</label>

<input
type="number"
step="0.01"
name="price_per_day"
value="{{ $item->price_per_day }}"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option
value="active"
{{ $item->status=='active' ? 'selected':'' }}>
Active
</option>

<option
value="inactive"
{{ $item->status=='inactive' ? 'selected':'' }}>
Inactive
</option>

</select>

</div>

<div class="col-md-12 mb-3">

<img
src="{{ asset('uploads/items/'.$item->image) }}"
width="120">

</div>

<div class="col-md-12 mb-3">

<input
type="file"
name="image"
class="form-control">

</div>

</div>

<button
class="btn btn-primary">

Update Item

</button>

</form>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor
        .create(document.querySelector('#description'))
        .catch(error => {
            console.error(error);
        });
});
</script>

</div>

</div>

@endsection
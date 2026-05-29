@extends('layouts.admin')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between">

<h4>
Category List
</h4>

<a
href="{{ route('category.create') }}"
class="btn btn-success">

Add Category

</a>

</div>

<div class="card-body">

<table
class="table table-bordered">

<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Name</th>

<th>Status</th>

<th width="180">
Action
</th>

</tr>

</thead>

<tbody>

@foreach($categories as $category)

<tr>

<td>
{{ $category->id }}
</td>

<td>

<img
src="{{ asset('uploads/category/'.$category->image) }}"
width="70">

</td>

<td>
{{ $category->name }}
</td>

<td>

@if($category->status=='active')

<span class="badge bg-success">
Active
</span>

@else

<span class="badge bg-danger">
Inactive
</span>

@endif

</td>

<td>

<a
href="{{ route('category.edit',$category->id) }}"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="{{ route('category.destroy',$category->id) }}"
method="POST"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('Delete Category?')"
class="btn btn-danger btn-sm">

Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
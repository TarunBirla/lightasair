@extends('layouts.admin')

@section('content')

<div class="card">

<div class="card-header d-flex justify-content-between">

<h4>Items List</h4>

<a
href="{{ route('items.create') }}"
class="btn btn-success">

Add Item

</a>

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Image</th>
<th>Category</th>
<th>Title</th>
<th>Qty</th>
<th>Available</th>
<th>Price/Day</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($items as $item)

<tr>

<td>{{ $item->id }}</td>

<td>
<img
src="{{ asset('uploads/items/'.$item->image) }}"
width="70">
</td>

<td>
{{ $item->category->name ?? '' }}
</td>

<td>
{{ $item->title }}
</td>

<td>
{{ $item->qty }}
</td>

<td>
{{ $item->available_qty }}
</td>

<td>
£{{ $item->price_per_day }}
</td>

<td>

@if($item->status=='active')

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
href="{{ route('items.edit',$item->id) }}"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="{{ route('items.destroy',$item->id) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button
onclick="return confirm('Delete Item?')"
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
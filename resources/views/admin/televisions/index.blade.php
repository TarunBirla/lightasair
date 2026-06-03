@extends('layouts.admin')

@section('page-title','Television')
@section('breadcrumb','Admin / Television')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h4>Television List</h4>

    <a href="{{ route('televisions.create') }}"
       class="btn btn-warning">
        Add Television
    </a>

</div>

<div class="card">
<div class="card-body">

<table class="table table-bordered table-striped">

<thead>

<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Title</th>
    <th>Tag</th>
    <th>Description</th>
    <th width="180">Action</th>
</tr>

</thead>

<tbody>

@foreach($televisions as $tv)

<tr>

<td>{{ $tv->id }}</td>

<td>
    <img src="{{ asset('uploads/televisions/'.$tv->image) }}"
         width="80">
</td>

<td>{{ $tv->title }}</td>

<td>{{ $tv->tag }}</td>

<td>
    {{ Str::limit($tv->description,100) }}
</td>

<td>

    <a href="{{ route('televisions.edit',$tv->id) }}"
       class="btn btn-primary btn-sm">
        Edit
    </a>

    <form action="{{ route('televisions.destroy',$tv->id) }}"
          method="POST"
          style="display:inline-block">

        @csrf
        @method('DELETE')

        <button class="btn btn-danger btn-sm"
                onclick="return confirm('Delete Television?')">
            Delete
        </button>

    </form>

</td>

</tr>

@endforeach

</tbody>

</table>

{{ $televisions->links() }}

</div>
</div>

@endsection
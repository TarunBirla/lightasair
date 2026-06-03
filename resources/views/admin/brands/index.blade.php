@extends('layouts.admin')

@section('page-title','Brands')
@section('breadcrumb','Admin / Brands')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Brands List</h4>

    <a href="{{ route('brands.create') }}"
       class="btn btn-warning">
        Add Brand
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
            <th width="180">Action</th>
        </tr>
    </thead>

    <tbody>

    @foreach($brands as $brand)

        <tr>

            <td>{{ $brand->id }}</td>

            <td>
                <img src="{{ asset('uploads/brands/'.$brand->image) }}"
                     width="80">
            </td>

            <td>{{ $brand->title }}</td>

            <td>

                <a href="{{ route('brands.edit',$brand->id) }}"
                   class="btn btn-primary btn-sm">
                    Edit
                </a>

                <form action="{{ route('brands.destroy',$brand->id) }}"
                      method="POST"
                      style="display:inline-block">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete Brand?')">
                        Delete
                    </button>

                </form>

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

{{ $brands->links() }}

</div>
</div>

@endsection
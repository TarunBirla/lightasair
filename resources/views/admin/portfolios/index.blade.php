@extends('layouts.admin')

@section('page-title','Portfolio')
@section('breadcrumb','Admin / Portfolio')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Portfolio List</h4>

    <a href="{{ route('portfolios.create') }}"
       class="btn btn-warning">
        Add Portfolio
    </a>
</div>

<table class="table table-bordered">

    <tr>
        <th>ID</th>
        <th>Image</th>
        <th width="180">Action</th>
    </tr>

    @foreach($portfolios as $portfolio)

    <tr>

        <td>{{ $portfolio->id }}</td>

        <td>
            <img src="{{ asset('uploads/portfolios/'.$portfolio->image) }}"
                 width="100">
        </td>

        <td>

            <a href="{{ route('portfolios.edit',$portfolio->id) }}"
               class="btn btn-primary btn-sm">
                Edit
            </a>

            <form action="{{ route('portfolios.destroy',$portfolio->id) }}"
                  method="POST"
                  style="display:inline">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">
                    Delete
                </button>

            </form>

        </td>

    </tr>

    @endforeach

</table>

{{ $portfolios->links() }}

@endsection
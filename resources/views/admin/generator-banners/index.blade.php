@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4>Generator Banners</h4>

        <a href="{{ route('generator-banners.create') }}"
           class="btn btn-primary">
            Add Banner
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Item</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
        </thead>

        <tbody>

        @foreach($banners as $banner)

        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                <img
                    src="{{ asset('uploads/generator-banner/'.$banner->image) }}"
                    width="100">
            </td>

            <td>{{ $banner->title }}</td>

            <td>
                {{ $banner->item->title ?? 'N/A' }}
            </td>

            <td>
                @if($banner->status)
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

                <a href="{{ route('generator-banners.edit',$banner->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form
                    action="{{ route('generator-banners.destroy',$banner->id) }}"
                    method="POST"
                    style="display:inline-block">

                    @csrf
                    @method('DELETE')

                    <button
                        onclick="return confirm('Delete Banner ?')"
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

@endsection
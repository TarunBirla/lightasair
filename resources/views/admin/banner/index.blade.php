@extends('layouts.admin')

@section('content')

<div class="container">

    <a href="{{ route('banner.create') }}"
       class="btn btn-primary mb-3">
       Add Banner
    </a>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        @foreach($banners as $banner)

        <tr>
            <td>{{ $banner->id }}</td>

            <td>{{ $banner->title }}</td>

            <td>
                <img
                src="{{ asset('uploads/banner/'.$banner->image) }}"
                width="100">
            </td>

            <td>{{ $banner->status }}</td>

            <td>

                <a href="{{ route('banner.edit',$banner->id) }}"
                class="btn btn-warning">
                Edit
                </a>

                <form
                action="{{ route('banner.destroy',$banner->id) }}"
                method="POST"
                style="display:inline">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Delete
                    </button>

                </form>

            </td>
        </tr>

        @endforeach

    </table>

</div>

@endsection
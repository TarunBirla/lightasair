@extends('layouts.admin')

@section('content')

<form action="{{ route('portfolios.update',$portfolio->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <img src="{{ asset('uploads/portfolios/'.$portfolio->image) }}"
                 width="150"
                 class="mb-3">

            <div class="mb-3">
                <label>Image</label>

                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <button class="btn btn-success">
                Update
            </button>

        </div>

    </div>

</form>

@endsection
@extends('layouts.admin')

@section('content')

<form action="{{ route('portfolios.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="card">
        <div class="card-body">

            <div class="mb-3">
                <label>Image</label>

                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <button class="btn btn-success">
                Save
            </button>

        </div>
    </div>

</form>

@endsection
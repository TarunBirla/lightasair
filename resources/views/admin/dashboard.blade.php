@extends('layouts.admin')

@section('content')

<div class="row">

<div class="col-md-3">

<div class="card text-white bg-primary">

<div class="card-body">

<h2>
{{ $bannerCount }}
</h2>

<p>
Total Banners
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-white bg-success">

<div class="card-body">

<h2>
{{ $categoryCount }}
</h2>

<p>
Total Categories
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-white bg-warning">

<div class="card-body">

<h2>
{{ $itemCount }}
</h2>

<p>
Total Items
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-white bg-danger">

<div class="card-body">

<h2>
{{ $userCount }}
</h2>

<p>
Total Users
</p>

</div>

</div>

</div>

</div>

@endsection
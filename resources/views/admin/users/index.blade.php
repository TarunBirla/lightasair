@extends('layouts.admin')

@section('content')

<div class="card shadow">

<div class="card-header">

<h4>
All Users
</h4>

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Mobile</th>

<th>Status</th>

<th>Created</th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>
{{ $user->id }}
</td>

<td>
{{ $user->name }}
</td>

<td>
{{ $user->email }}
</td>

<td>
{{ $user->mobile }}
</td>

<td>

@if($user->status=='active')

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
{{ $user->created_at }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
@extends('layouts.admin')

@section('content')

<div class="card shadow">

<div class="card-header">

<h4>
All Bookings
</h4>

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>

<th>Booking No</th>

<th>User</th>

<th>Email</th>

<th>Start Date</th>

<th>End Date</th>

<th>Days</th>

<th>Total</th>

<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($bookings as $booking)

<tr>

<td>
{{ $booking->id }}
</td>

<td>
{{ $booking->booking_no }}
</td>

<td>
{{ $booking->user->name ?? '' }}
</td>

<td>
{{ $booking->user->email ?? '' }}
</td>

<td>
{{ $booking->start_date }}
</td>

<td>
{{ $booking->end_date }}
</td>

<td>
{{ $booking->total_days }}
</td>

<td>

£{{ number_format($booking->total_amount,2) }}

</td>

<td>

@if($booking->status == 'pending')

<span class="badge bg-warning text-dark">
    Pending
</span>

@elseif($booking->status == 'approved')

<span class="badge bg-success">
    Approved
</span>

@elseif($booking->status == 'completed')

<span class="badge bg-primary">
    Completed
</span>

@elseif($booking->status == 'rejected')

<span class="badge bg-danger">
    Rejected
</span>

@else

<span class="badge bg-secondary">
    Unknown
</span>

@endif

</td>
<td>

<a
href="{{ route('admin.booking.show',$booking->id) }}"
class="btn btn-primary btn-sm">

View

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
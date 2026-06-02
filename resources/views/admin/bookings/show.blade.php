@extends('layouts.admin')

@section('content')
<style>
    .headerdetails{
        background-color: #FFC700;
        color:black;
    }
</style>

    <div class="container-fluid">

        <div class="card shadow">

            <div class="card-header headerdetails">

                <h4>

                    Request Detail

                </h4>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-bordered">

                            <tr>

                                <th>
                                    Request No
                                </th>

                                <td>

                                    {{ $booking->booking_no }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Customer Name
                                </th>

                                <td>

                                    {{ $booking->user->name }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Email
                                </th>

                                <td>

                                    {{ $booking->user->email }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Mobile
                                </th>

                                <td>

                                    {{ $booking->user->mobile }}

                                </td>

                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-bordered">

                            <tr>

                                <th>
                                    Start Date
                                </th>

                                <td>

                                    {{ $booking->start_date }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    End Date
                                </th>

                                <td>

                                    {{ $booking->end_date }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Total Days
                                </th>

                                <td>

                                    {{ $booking->total_days }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Status
                                </th>

                                <td>

                                    @if($booking->status == 'pending')

                                        <span class="badge bg-warning">

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

                                    @else

                                        <span class="badge bg-danger">

                                            Rejected

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

                <hr>

                <h4>

                    Request Items

                </h4>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>
                                Image
                            </th>

                            <th>
                                Item
                            </th>

                            <th>
                                Qty
                            </th>

                            <th>
                                Price Per Day
                            </th>

                            <th>
                                Days
                            </th>

                            <th>
                                Total
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($booking->items as $row)

                            <tr>

                                <td>

                                    <img src="{{ asset('uploads/items/' . $row->item->image) }}" width="80">

                                </td>

                                <td>

                                    {{ $row->item->title }}

                                </td>

                                <td>

                                    {{ $row->qty }}

                                </td>

                                <td>

                                    £{{ number_format($row->price_per_day, 2) }}

                                </td>

                                <td>

                                    {{ $row->total_days }}

                                </td>

                                <td>

                                    £{{ number_format($row->total_amount, 2) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="text-end">

                    <h4>

                        Grand Total :

                        £{{ number_format($booking->total_amount, 2) }}

                    </h4>

                </div>

                <hr>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.booking.approve', $booking->id) }}" class="btn btn-success">

                        Approve

                    </a>

                    <a href="{{ route('admin.booking.reject', $booking->id) }}" class="btn btn-danger">

                        Reject

                    </a>

                    <a href="{{ route('admin.booking.complete', $booking->id) }}" class="btn btn-primary">

                        Complete

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection
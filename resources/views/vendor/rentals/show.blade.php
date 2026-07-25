@extends('layouts.vendor')

@section('title', 'Rental Listing Preview')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">{{ $rental->title }}</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Rental listing preview — Status: <span class="badge badge-{{ $rental->status }}">{{ ucfirst($rental->status) }}</span></p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('vendor.rentals.calendar', $rental->id) }}" class="btn btn-outline-brand" style="border-radius:10px;">Calendar</a>
        <a href="{{ route('vendor.rentals.edit', $rental->id) }}" class="btn btn-brand" style="border-radius:10px;">Edit Listing</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Gallery</h4>
            @if($rental->images && count($rental->images))
                <img src="{{ asset('storage/' . $rental->images[0]) }}" class="img-fluid rounded mb-3" style="max-height:350px;width:100%;object-fit:cover;">
            @else
                <div class="p-5 text-center text-muted background-light rounded mb-3">No images uploaded</div>
            @endif

            <h4 style="font-size:1.1rem;font-weight:800;margin-top:1.5rem;margin-bottom:1rem;">Description</h4>
            <div style="font-size:.9rem;line-height:1.7;color:#444;white-space:pre-line;">{{ $rental->description }}</div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Rental Details</h4>
            <table class="table table-borderless font-size-sm">
                <tr><td class="text-muted">Daily Rate:</td><td class="fw-bold">£{{ number_format($rental->price_per_day, 2) }}</td></tr>
                <tr><td class="text-muted">Weekly Rate:</td><td class="fw-bold">£{{ number_format($rental->price_per_week ?? 0, 2) }}</td></tr>
                <tr><td class="text-muted">Security Deposit:</td><td class="fw-bold">£{{ number_format($rental->deposit_amount ?? 0, 2) }}</td></tr>
                <tr><td class="text-muted">Quantity:</td><td class="fw-bold">{{ $rental->total_qty }} unit(s)</td></tr>
                <tr><td class="text-muted">Min Rental:</td><td class="fw-bold">{{ $rental->min_rental_days }} day(s)</td></tr>
                <tr><td class="text-muted">Location:</td><td class="fw-bold">{{ $rental->location ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Recent Bookings</h4>
            @if($rental->rentalBookings->isEmpty())
                <p class="text-muted" style="font-size:.85rem;">No bookings for this equipment yet.</p>
            @else
                <ul class="list-group list-group-flush" style="font-size:.85rem;">
                    @foreach($rental->rentalBookings->take(5) as $booking)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <strong style="display:block;">{{ $booking->customer->name ?? 'Customer' }}</strong>
                                <span class="text-muted" style="font-size:.78rem;">{{ $booking->start_date }} → {{ $booking->end_date }}</span>
                            </div>
                            <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

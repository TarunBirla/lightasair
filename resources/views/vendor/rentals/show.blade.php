@extends('layouts.vendor')

@section('title', 'Rental Listing Preview — '.$rental->title)

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;margin:0;">{{ $rental->title }}</h1>
        <p style="color:#888;font-size:.9rem;margin:.2rem 0 0 0;">
            Category: <strong>{{ $rental->category->name ?? 'Equipment' }}</strong> · Status:
            @if($rental->isApproved())
                <span class="status-badge badge-approved">Approved</span>
            @elseif($rental->isPending())
                <span class="status-badge badge-pending">Pending Approval</span>
            @elseif($rental->isRejected())
                <span class="status-badge badge-rejected">Rejected</span>
            @else
                <span class="status-badge" style="background:#eee;color:#555;">{{ ucfirst($rental->status) }}</span>
            @endif
        </p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('vendor.rentals.calendar', $rental->id) }}" class="btn btn-outline-dark" style="border-radius:10px;font-weight:700;font-size:.85rem;">
            <i class="bi bi-calendar-event me-1"></i> Availability Calendar
        </a>
        <a href="{{ route('vendor.rentals.edit', $rental->id) }}" class="btn-brand" style="border-radius:10px;font-size:.85rem;">
            <i class="bi bi-pencil-square me-1"></i> Edit Listing
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="content-card p-4">
            <h4 class="content-card-title mb-3"><i class="bi bi-images me-2 text-warning"></i> Equipment Photos</h4>
            @php $imageUrls = $rental->allImageUrls(); @endphp
            <div class="mb-3" style="border-radius:12px;overflow:hidden;background:#f5f4ef;border:1px solid #e5e4df;">
                <img id="mainPreviewImg" src="{{ $rental->primaryImageUrl() }}" alt="{{ $rental->title }}" style="width:100%;height:360px;object-fit:contain;">
            </div>
            @if(count($imageUrls) > 1)
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    @foreach($imageUrls as $i => $url)
                        <img src="{{ $url }}" onclick="document.getElementById('mainPreviewImg').src='{{ $url }}'" style="width:75px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #ddd;">
                    @endforeach
                </div>
            @endif

            <h4 class="content-card-title mt-4 mb-3"><i class="bi bi-file-text me-2 text-warning"></i> Description</h4>
            <div style="font-size:.92rem;line-height:1.75;color:#444;white-space:pre-line;">{{ $rental->description }}</div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="content-card p-4 mb-4">
            <h4 class="content-card-title mb-3"><i class="bi bi-currency-pound me-2 text-warning"></i> Rates & Specifications</h4>
            <table class="table table-borderless align-middle mb-0" style="font-size:.88rem;">
                <tr><td class="text-muted ps-0">Daily Rate:</td><td class="text-end fw-bold fs-5 text-dark">£{{ number_format($rental->price_per_day, 2) }} / day</td></tr>
                @if($rental->price_per_week > 0)
                    <tr><td class="text-muted ps-0">Weekly Rate:</td><td class="text-end fw-bold text-success">£{{ number_format($rental->price_per_week, 2) }} / wk</td></tr>
                @endif
                @if($rental->deposit_amount > 0)
                    <tr><td class="text-muted ps-0">Security Deposit:</td><td class="text-end fw-bold text-secondary">£{{ number_format($rental->deposit_amount, 2) }}</td></tr>
                @endif
                <tr><td class="text-muted ps-0">Available Units:</td><td class="text-end fw-bold">{{ $rental->total_qty }} unit(s)</td></tr>
                <tr><td class="text-muted ps-0">Min Rental Days:</td><td class="text-end fw-bold">{{ $rental->min_rental_days }} day(s)</td></tr>
                <tr><td class="text-muted ps-0">Condition:</td><td class="text-end fw-bold">{{ ucfirst($rental->condition) }}</td></tr>
                @if($rental->brand)
                    <tr><td class="text-muted ps-0">Brand:</td><td class="text-end fw-bold">{{ $rental->brand }}</td></tr>
                @endif
                @if($rental->model_number)
                    <tr><td class="text-muted ps-0">Model:</td><td class="text-end fw-bold">{{ $rental->model_number }}</td></tr>
                @endif
                @if($rental->location)
                    <tr><td class="text-muted ps-0">Location:</td><td class="text-end fw-bold">{{ $rental->location }}</td></tr>
                @endif
            </table>
        </div>

        <div class="content-card p-4">
            <h4 class="content-card-title mb-3"><i class="bi bi-calendar-check me-2 text-warning"></i> Recent Bookings</h4>
            @if($rental->rentalBookings->isEmpty())
                <p class="text-muted" style="font-size:.85rem;margin:0;">No bookings for this equipment yet.</p>
            @else
                <div class="list-group list-group-flush">
                    @foreach($rental->rentalBookings->take(5) as $booking)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div style="font-weight:700;font-size:.85rem;">{{ $booking->customer->name ?? 'Customer' }}</div>
                                <div class="text-muted" style="font-size:.78rem;">{{ $booking->start_date }} → {{ $booking->end_date }}</div>
                            </div>
                            <span class="badge bg-secondary" style="font-size:.7rem;">{{ ucfirst($booking->status) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

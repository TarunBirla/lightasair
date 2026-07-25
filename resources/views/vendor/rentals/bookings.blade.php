@extends('layouts.vendor')

@section('title', 'Incoming Rental Bookings')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">Rental Bookings</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Manage incoming customer equipment hire requests</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif

<div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
    @if($bookings->isEmpty())
        <div style="text-align:center;padding:3rem 1rem;color:#888;">
            <i class="bi bi-calendar-check" style="font-size:2.5rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
            <h4>No rental bookings received yet</h4>
            <p>Customer rental requests will show up here once placed.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr style="font-size:.78rem;text-transform:uppercase;color:#888;">
                        <th>Booking Ref</th>
                        <th>Equipment</th>
                        <th>Customer</th>
                        <th>Rental Dates</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td style="font-family:monospace;font-weight:800;color:#111;">{{ $booking->booking_ref }}</td>
                        <td style="font-weight:700;">{{ $booking->listing->title ?? 'Deleted Listing' }}</td>
                        <td>
                            <div style="font-weight:700;">{{ $booking->customer->name ?? '—' }}</div>
                            <small class="text-muted">{{ $booking->customer->email ?? '' }}</small>
                        </td>
                        <td style="font-size:.85rem;">
                            {{ $booking->start_date }} → {{ $booking->end_date }}
                            <div class="text-muted" style="font-size:.75rem;">{{ $booking->total_days }} day(s)</div>
                        </td>
                        <td style="font-weight:800;color:#16a34a;">£{{ number_format($booking->total_amount, 2) }}</td>
                        <td><span class="status-badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                        <td>
                            <form action="{{ route('vendor.rental-bookings.status', $booking->id) }}" method="POST" style="display:inline-flex;gap:.3rem;">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:130px;border-radius:8px;" onchange="this.form.submit()">
                                    <option value="pending" @selected($booking->status === 'pending')>Pending</option>
                                    <option value="confirmed" @selected($booking->status === 'confirmed')>Confirmed</option>
                                    <option value="active" @selected($booking->status === 'active')>Active / Dispatched</option>
                                    <option value="returned" @selected($booking->status === 'returned')>Returned</option>
                                    <option value="completed" @selected($booking->status === 'completed')>Completed</option>
                                    <option value="cancelled" @selected($booking->status === 'cancelled')>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

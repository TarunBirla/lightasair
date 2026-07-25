@extends('front.layouts.app')

@section('title', 'My Rental Bookings — Light As Air')

@section('content')

<style>
.my-rentals-wrap { padding: 3rem 0; background: #F5F4EF; min-height: 80vh; }
.card-table { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.04); }

.rent-table { width: 100%; border-collapse: collapse; text-align: left; }
.rent-table th { background: #fafaf8; font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #888; padding: 1rem 1.2rem; border-bottom: 1px solid #e5e4df; }
.rent-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #f0ede8; font-size: .9rem; vertical-align: middle; }
.rent-table tr:last-child td { border: none; }

.badge-status { padding: .25rem .75rem; border-radius: 20px; font-size: .72rem; font-weight: 800; text-transform: uppercase; }
.badge-pending   { background: #fff3cd; color: #856404; }
.badge-confirmed { background: #dcfce7; color: #166534; }
.badge-active    { background: #dbeafe; color: #1e40af; }
.badge-completed { background: #f3f4f6; color: #374151; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }
</style>

<div class="my-rentals-wrap">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.8rem;font-weight:900;margin:0;">My Rental Bookings</h1>
                <p style="color:#888;font-size:.9rem;margin:0;">Track and manage your equipment hire requests</p>
            </div>
            <a href="{{ route('front.rentals') }}" class="btn btn-dark" style="border-radius:10px;font-weight:700;">
                <i class="bi bi-plus-lg me-1"></i> Rent Equipment
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
        @endif

        <div class="card-table">
            @if($bookings->isEmpty())
                <div style="text-align:center;padding:4rem 2rem;color:#888;">
                    <i class="bi bi-calendar-x" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
                    <h3>No rental bookings yet</h3>
                    <p>When you request equipment hire, your bookings will appear here.</p>
                    <a href="{{ route('front.rentals') }}" class="btn btn-primary mt-2" style="background:#16a34a;border:none;border-radius:10px;font-weight:700;">Browse Rentals</a>
                </div>
            @else
                <table class="rent-table">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Equipment</th>
                            <th>Rental Dates</th>
                            <th>Days</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td style="font-family:monospace;font-weight:700;color:#16a34a;">{{ $booking->booking_ref }}</td>
                            <td style="font-weight:700;">{{ $booking->listing->title ?? 'Listing Removed' }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} → {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</td>
                            <td style="font-weight:700;">{{ $booking->total_days }} day(s)</td>
                            <td style="font-weight:900;">£{{ number_format($booking->total_amount, 2) }}</td>
                            <td><span class="badge-status badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                            <td>
                                @if($booking->isPending() || $booking->isConfirmed())
                                    <form action="{{ route('front.rentals.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Cancel this booking request?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:.78rem;font-weight:700;">Cancel</button>
                                    </form>
                                @else
                                    <span style="color:#aaa;font-size:.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div style="margin-top:1.5rem;display:flex;justify-content:center;">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

@endsection

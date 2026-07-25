@extends('layouts.admin')

@section('page-title', Str::limit($auction->title, 40))
@section('breadcrumb', 'Admin / Auctions / Detail')

@section('content')

<a href="/admin/auctions" class="text-muted d-inline-block mb-3" style="text-decoration:none;">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Auctions
</a>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card p-4 mb-4" style="border-radius:14px;border:1px solid #E8E6DF;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Bidding History ({{ $auction->bid_count }})</h4>

            @if($auction->bids->isEmpty())
                <p class="text-muted">No bids placed on this auction yet.</p>
            @else
                <table class="table table-hover align-middle">
                    <thead>
                        <tr style="font-size:.75rem;text-transform:uppercase;color:#888;">
                            <th>Bidder</th>
                            <th>Amount</th>
                            <th>IP Address</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auction->bids as $bid)
                        <tr>
                            <td style="font-weight:700;">{{ $bid->bidder->name ?? 'User #' . $bid->user_id }}</td>
                            <td style="font-weight:800;color:#16a34a;">£{{ number_format($bid->amount, 2) }}</td>
                            <td style="font-size:.8rem;color:#888;">{{ $bid->ip_address ?? '—' }}</td>
                            <td style="font-size:.8rem;color:#888;">{{ $bid->created_at->format('d M Y H:i:s') }}</td>
                            <td>
                                @if($bid->is_winning)
                                    <span class="badge bg-success">Highest</span>
                                @else
                                    <span class="badge bg-light text-dark">Outbid</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card p-4" style="border-radius:14px;border:1px solid #E8E6DF;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Description</h4>
            <div style="font-size:.9rem;line-height:1.7;color:#444;white-space:pre-line;">{{ $auction->description }}</div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4 mb-4" style="border-radius:14px;border:1px solid #E8E6DF;">
            <h3 style="font-size:1.25rem;font-weight:800;margin-bottom:1rem;">{{ $auction->title }}</h3>
            <table class="table table-borderless font-size-sm">
                <tr><td class="text-muted">Vendor:</td><td class="fw-bold">{{ $auction->vendor->name }}</td></tr>
                <tr><td class="text-muted">Category:</td><td class="fw-bold">{{ $auction->category->name ?? '—' }}</td></tr>
                <tr><td class="text-muted">Current Bid:</td><td class="fw-bold text-success" style="font-size:1.2rem;">£{{ number_format($auction->current_bid, 2) }}</td></tr>
                <tr><td class="text-muted">Starting Bid:</td><td class="fw-bold">£{{ number_format($auction->starting_bid, 2) }}</td></tr>
                <tr><td class="text-muted">Reserve Price:</td><td class="fw-bold">£{{ number_format($auction->reserve_price, 2) }}</td></tr>
                <tr><td class="text-muted">Reserve Met?</td><td class="fw-bold">{{ $auction->reserveMet() ? '✓ Yes' : '✗ No' }}</td></tr>
                <tr><td class="text-muted">Start Time:</td><td class="fw-bold">{{ $auction->start_time ? $auction->start_time->format('d M Y H:i') : '—' }}</td></tr>
                <tr><td class="text-muted">End Time:</td><td class="fw-bold">{{ $auction->end_time ? $auction->end_time->format('d M Y H:i') : '—' }}</td></tr>
                <tr><td class="text-muted">Status:</td><td class="fw-bold"><span class="badge bg-secondary">{{ ucfirst($auction->status) }}</span></td></tr>
                @if($auction->winner)
                <tr><td class="text-muted">Winner:</td><td class="fw-bold text-success">{{ $auction->winner->name }} (£{{ number_format($auction->winning_bid, 2) }})</td></tr>
                @endif
            </table>
        </div>

        <div class="card p-4" style="border-radius:14px;border:1px solid #E8E6DF;">
            <h4 style="font-size:1rem;font-weight:800;margin-bottom:1rem;">Admin Controls</h4>
            @if($auction->isPending())
                <form action="/admin/auctions/{{ $auction->id }}/approve" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 font-weight-bold">Approve & Activate Auction</button>
                </form>
            @endif
            @if($auction->isActive())
                <form action="/admin/auctions/{{ $auction->id }}/close" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-dark w-100 font-weight-bold">Close Auction Now</button>
                </form>
            @endif
            <form action="/admin/auctions/{{ $auction->id }}/toggle-featured" method="POST" class="mb-2">
                @csrf
                <button type="submit" class="btn btn-warning w-100 font-weight-bold">
                    {{ $auction->is_featured ? 'Unmark Featured' : 'Mark as Featured' }}
                </button>
            </form>
            <form action="/admin/auctions/{{ $auction->id }}" method="POST" onsubmit="return confirm('Permanently delete this auction?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100 font-weight-bold">Delete Auction</button>
            </form>
        </div>
    </div>
</div>
@endsection

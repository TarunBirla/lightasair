@extends('layouts.vendor')

@section('title', 'Auction Details')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">{{ $auction->title }}</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Auction Status: <span class="badge badge-{{ $auction->status }}">{{ ucfirst($auction->status) }}</span></p>
    </div>
    <a href="{{ route('vendor.auctions.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;">Back to Auctions</a>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Live Bid History ({{ $auction->bid_count }})</h4>

            @if($auction->bids->isEmpty())
                <p class="text-muted" style="font-size:.9rem;">No bids placed yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr style="font-size:.75rem;text-transform:uppercase;color:#888;">
                                <th>Bidder</th>
                                <th>Amount</th>
                                <th>Placed At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auction->bids as $bid)
                            <tr>
                                <td>{{ $bid->bidder->name ?? 'Anonymous' }}</td>
                                <td style="font-weight:800;color:#16a34a;">£{{ number_format($bid->amount, 2) }}</td>
                                <td style="font-size:.8rem;color:#888;">{{ $bid->created_at->format('d M Y H:i:s') }}</td>
                                <td>
                                    @if($bid->is_winning)
                                        <span class="badge bg-success">Highest Bid</span>
                                    @else
                                        <span class="badge bg-light text-dark">Outbid</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4 mb-4" style="border-radius:16px;border:1px solid #e5e4df;">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;">Auction Summary</h4>
            <table class="table table-borderless font-size-sm">
                <tr><td class="text-muted">Current Bid:</td><td class="fw-bold" style="font-size:1.2rem;color:#16a34a;">£{{ number_format($auction->current_bid, 2) }}</td></tr>
                <tr><td class="text-muted">Starting Bid:</td><td class="fw-bold">£{{ number_format($auction->starting_bid, 2) }}</td></tr>
                <tr><td class="text-muted">Reserve Price:</td><td class="fw-bold">£{{ number_format($auction->reserve_price, 2) }}</td></tr>
                <tr><td class="text-muted">Reserve Met?</td><td class="fw-bold">{{ $auction->reserveMet() ? '✓ Yes' : '✗ No' }}</td></tr>
                <tr><td class="text-muted">Min Increment:</td><td class="fw-bold">£{{ number_format($auction->min_increment, 2) }}</td></tr>
                <tr><td class="text-muted">End Time:</td><td class="fw-bold">{{ $auction->end_time ? $auction->end_time->format('d M Y H:i') : '—' }}</td></tr>
                @if($auction->winner)
                <tr><td class="text-muted">Winning Bidder:</td><td class="fw-bold text-success">{{ $auction->winner->name }} (£{{ number_format($auction->winning_bid, 2) }})</td></tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

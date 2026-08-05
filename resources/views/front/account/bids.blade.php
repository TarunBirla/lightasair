@extends('front.account.layout')

@section('title', 'My Auction Bids — Light As Air')

@section('account_content')

<style>
.card-table { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.04); }
.bids-table { width: 100%; border-collapse: collapse; text-align: left; }
.bids-table th { background: #fafaf8; font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: #888; padding: 1rem 1.2rem; border-bottom: 1px solid #e5e4df; }
.bids-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #f0ede8; font-size: .9rem; vertical-align: middle; }
.bids-table tr:last-child td { border: none; }
.bids-table tr:hover td { background: #fefefc; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.6rem;font-weight:900;margin:0;color:#111;">My Auction Bids</h2>
        <p style="color:#888;font-size:.88rem;margin:0;">Track live timed auction bids and winning items</p>
    </div>
    <a href="/auctions" class="btn btn-dark" style="border-radius:10px;font-weight:700;">
        <i class="bi bi-hammer me-1"></i> Live Auctions
    </a>
</div>

<div class="card-table">
    @if($bids->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;color:#888;">
            <i class="bi bi-gavel" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:.4;"></i>
            <h4 style="font-weight:800;color:#111;">No bids placed yet</h4>
            <p>Participate in timed auctions to win film lighting equipment.</p>
            <a href="/auctions" class="btn btn-warning mt-2" style="font-weight:800;border-radius:10px;">Browse Live Auctions</a>
        </div>
    @else
        <table class="bids-table">
            <thead>
                <tr>
                    <th>Auction Item</th>
                    <th>My Bid Amount</th>
                    <th>Auction Status</th>
                    <th>Bid Time</th>
                    <th>My Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                <tr>
                    <td>
                        <div style="font-weight:700;color:#111;">{{ $bid->auction->title ?? 'Auction Listing' }}</div>
                        <div style="font-size:.78rem;color:#888;">Seller: {{ $bid->auction->vendor->name ?? 'Vendor' }}</div>
                    </td>
                    <td style="font-weight:900;color:#16a34a;font-size:1rem;">£{{ number_format($bid->amount, 2) }}</td>
                    <td>
                        @if($bid->auction?->isActive())
                            <span class="badge bg-success" style="font-size:.7rem;">Active Bidding</span>
                        @elseif($bid->auction?->isEnded())
                            <span class="badge bg-secondary" style="font-size:.7rem;">Auction Ended</span>
                        @else
                            <span class="badge bg-light text-dark" style="font-size:.7rem;">{{ ucfirst($bid->auction?->status ?? 'ended') }}</span>
                        @endif
                    </td>
                    <td style="font-size:.85rem;color:#666;">{{ $bid->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($bid->is_winning)
                            <span class="badge bg-success px-2 py-1"><i class="bi bi-trophy-fill me-1"></i> Highest Bidder</span>
                        @else
                            <span class="badge bg-light text-dark border px-2 py-1">Outbid</span>
                        @endif
                    </td>
                    <td>
                        @if($bid->auction)
                            <a href="/auctions/{{ $bid->auction->slug }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-weight:700;font-size:.78rem;">
                                View Listing
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $bids->links() }}
</div>

@endsection

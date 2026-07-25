@extends('layouts.vendor')

@section('title', 'My Auctions')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">My Auctions</h1>
        <p style="color:#888;font-size:.9rem;margin:0;">Create and monitor your timed equipment auctions</p>
    </div>
    <a href="{{ route('vendor.auctions.create') }}" class="btn btn-brand" style="border-radius:10px;font-weight:700;">
        <i class="bi bi-hammer me-1"></i> Create Auction
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

<div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
    @if($auctions->isEmpty())
        <div style="text-align:center;padding:3rem 1rem;color:#888;">
            <i class="bi bi-hammer" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
            <h4>No auctions listed yet</h4>
            <p>List equipment for competitive timed bidding.</p>
            <a href="{{ route('vendor.auctions.create') }}" class="btn btn-brand mt-2" style="border-radius:10px;">Create First Auction</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr style="font-size:.78rem;text-transform:uppercase;color:#888;">
                        <th>Auction Title</th>
                        <th>Current Bid</th>
                        <th>Reserve</th>
                        <th>Bids</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auctions as $auction)
                    <tr>
                        <td style="font-weight:700;">
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <img src="{{ $auction->primaryImageUrl() }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                                <div>
                                    <a href="{{ route('vendor.auctions.show', $auction->id) }}" style="color:#111;text-decoration:none;">{{ Str::limit($auction->title, 40) }}</a>
                                    <div class="text-muted" style="font-size:.75rem;">{{ $auction->category->name ?? 'Uncategorised' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:800;color:#16a34a;">£{{ number_format($auction->current_bid, 2) }}</td>
                        <td style="font-size:.85rem;">£{{ number_format($auction->reserve_price, 2) }}</td>
                        <td style="font-weight:700;">{{ $auction->bid_count }} bid(s)</td>
                        <td style="font-size:.82rem;">{{ $auction->end_time ? $auction->end_time->format('d M Y H:i') : '—' }}</td>
                        <td><span class="badge badge-{{ $auction->status }}">{{ ucfirst($auction->status) }}</span></td>
                        <td>
                            <div style="display:flex;gap:.3rem;">
                                <a href="{{ route('vendor.auctions.show', $auction->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">View</a>
                                @unless($auction->isActive() || $auction->isEnded())
                                    <a href="{{ route('vendor.auctions.edit', $auction->id) }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;">Edit</a>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $auctions->links() }}</div>
    @endif
</div>
@endsection

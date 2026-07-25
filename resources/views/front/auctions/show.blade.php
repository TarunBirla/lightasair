@extends('front.layouts.app')

@section('title', $auction->title . ' — Timed Auction')
@section('meta_description', Str::limit(strip_tags($auction->description), 155))

@section('content')

<style>
.ad-breadcrumb { background: #f5f4ef; padding: .85rem 0; border-bottom: 1px solid #e8e6de; font-size: .8rem; color: #888; }
.ad-breadcrumb a { color: #888; text-decoration: none; }
.ad-breadcrumb a:hover { color: #ec4899; }
.ad-breadcrumb span { margin: 0 .4rem; }

.ad-section { background: #F5F4EF; padding: 2.5rem 0 4rem; }

.ad-grid { display: grid; grid-template-columns: 1fr 420px; gap: 2rem; align-items: start; }
@media(max-width: 960px) { .ad-grid { grid-template-columns: 1fr; } }

.ad-gallery { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; }
.ad-main-img { width: 100%; height: 420px; object-fit: contain; background: #f8f8f6; display: block; }
.ad-thumbs { display: flex; gap: .6rem; padding: 1rem; overflow-x: auto; background: #fafaf8; border-top: 1px solid #f0ede8; }
.ad-thumb { width: 80px; height: 68px; flex-shrink: 0; object-fit: cover; border-radius: 8px; border: 2px solid transparent; cursor: pointer; }
.ad-thumb.active { border-color: #ec4899; }

.ad-card { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 1.6rem; position: sticky; top: 80px; }
.ad-title { font-size: 1.4rem; font-weight: 900; color: #111; line-height: 1.25; margin-bottom: .4rem; }

.countdown-box {
    background: linear-gradient(135deg, #111 0%, #222 100%);
    color: #fff; border-radius: 14px; padding: 1.2rem;
    text-align: center; margin-bottom: 1.2rem;
}
.timer-display { font-size: 1.8rem; font-weight: 900; font-family: monospace; color: #ec4899; }

.price-box { background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 12px; padding: 1rem; margin-bottom: 1.2rem; }
.bid-current { font-size: 2.2rem; font-weight: 900; color: #be185d; line-height: 1; }

.btn-place-bid {
    width: 100%; padding: 1rem; border: none; border-radius: 12px;
    background: #ec4899; color: #fff; font-weight: 900; font-size: 1rem;
    cursor: pointer; transition: background .2s;
}
.btn-place-bid:hover { background: #db2777; }

.bids-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.bids-table th { padding: .6rem; border-bottom: 1px solid #eee; text-align: left; color: #888; font-size: .75rem; text-transform: uppercase; }
.bids-table td { padding: .65rem .6rem; border-bottom: 1px solid #f8f8f8; }

.ad-desc { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 1.8rem; margin-top: 1.5rem; }
.ad-desc h2 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; padding-bottom: .4rem; border-bottom: 2px solid #ec4899; display: inline-block; }
</style>

<div class="ad-breadcrumb">
    <div class="container">
        <a href="/">Home</a><span>/</span>
        <a href="{{ route('front.auctions') }}">Auctions</a><span>/</span>
        {{ Str::limit($auction->title, 45) }}
    </div>
</div>

<div class="ad-section">
    <div class="container">
        <div class="ad-grid">

            <div>
                {{-- Gallery --}}
                <div class="ad-gallery">
                    @if($auction->images && count($auction->images))
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" id="adMainImg" class="ad-main-img" alt="{{ $auction->title }}">
                        @if(count($auction->images) > 1)
                        <div class="ad-thumbs">
                            @foreach($auction->images as $i => $img)
                            <img src="{{ asset('storage/' . $img) }}" class="ad-thumb {{ $i === 0 ? 'active' : '' }}"
                                 onclick="document.getElementById('adMainImg').src=this.src; document.querySelectorAll('.ad-thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active')">
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div style="height:380px;display:flex;align-items:center;justify-content:center;background:#f8f8f6;color:#ccc;">
                            <i class="bi bi-image" style="font-size:3rem;"></i>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="ad-desc">
                    <h2>Auction Description</h2>
                    <div style="font-size:.92rem;line-height:1.8;color:#444;white-space:pre-line;">{{ $auction->description }}</div>
                </div>

                {{-- Live Bid Feed --}}
                <div class="ad-desc">
                    <h2>Live Bid Feed ({{ $auction->bid_count }})</h2>
                    @if($auction->bids->isEmpty())
                        <p class="text-muted">No bids placed yet. Be the first to bid!</p>
                    @else
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th>Bidder</th>
                                    <th>Bid Amount</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auction->bids as $bid)
                                <tr>
                                    <td style="font-weight:700;">{{ Str::mask($bid->bidder->name ?? 'Bidder', '*', 2) }}</td>
                                    <td style="font-weight:900;color:#be185d;">£{{ number_format($bid->amount, 2) }}</td>
                                    <td style="color:#888;">{{ $bid->created_at->diffForHumans() }}</td>
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
                    @endif
                </div>
            </div>

            {{-- Right Bidding Card --}}
            <div>
                <div class="ad-card">
                    <h1 class="ad-title">{{ $auction->title }}</h1>
                    <p style="font-size:.85rem;color:#888;margin-bottom:1rem;"><i class="bi bi-shop me-1"></i> Seller: {{ $auction->vendor->name }}</p>

                    {{-- Countdown Timer --}}
                    <div class="countdown-box">
                        <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#aaa;margin-bottom:.3rem;">Time Remaining</div>
                        <div class="timer-display" id="detailTimer">00h 00m 00s</div>
                    </div>

                    <div class="price-box">
                        <div style="font-size:.78rem;color:#be185d;font-weight:700;text-transform:uppercase;">Current Highest Bid</div>
                        <div class="bid-current">£{{ number_format($auction->current_bid, 2) }}</div>
                        <div style="font-size:.8rem;color:#888;margin-top:.3rem;">
                            {{ $auction->bid_count }} bid(s) · Min Next Bid: <strong>£{{ number_format($auction->nextMinBid(), 2) }}</strong>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mb-3" style="border-radius:10px;font-size:.85rem;">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mb-3" style="border-radius:10px;font-size:.85rem;">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger mb-3" style="border-radius:10px;font-size:.85rem;">{{ $errors->first() }}</div>
                    @endif

                    @if($auction->isActive())
                        @auth
                            <form method="POST" action="{{ route('front.auctions.bid', $auction->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;">Your Bid Amount (£)</label>
                                    <input type="number" step="0.01" name="amount" class="form-control form-control-lg"
                                           value="{{ old('amount', number_format($auction->nextMinBid(), 2, '.', '')) }}"
                                           min="{{ $auction->nextMinBid() }}" required style="border-radius:10px;font-weight:800;">
                                </div>
                                <button type="submit" class="btn-place-bid">
                                    <i class="bi bi-hammer me-2"></i> Place Bid Now
                                </button>
                            </form>
                        @else
                            <a href="/login" class="btn-place-bid" style="display:block;text-align:center;text-decoration:none;">
                                Log in to Place Bid
                            </a>
                        @endauth
                    @else
                        <div class="alert alert-secondary text-center font-weight-bold" style="border-radius:10px;">
                            This auction has ended.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const end = new Date("{{ $auction->end_time ? $auction->end_time->toIso8601String() : '' }}").getTime();

    function updateDetailTimer() {
        const now = new Date().getTime();
        const diff = end - now;

        if (diff <= 0) {
            document.getElementById('detailTimer').textContent = "AUCTION CLOSED";
            document.getElementById('detailTimer').style.color = "#dc2626";
        } else {
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);
            document.getElementById('detailTimer').textContent = `${hours}h ${mins}m ${secs}s`;
        }
    }
    if (end) {
        updateDetailTimer();
        setInterval(updateDetailTimer, 1000);
    }
});
</script>

@endsection

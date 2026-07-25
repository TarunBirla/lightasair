@extends('front.layouts.app')

@section('title', 'Timed Auctions — Light As Air Marketplace')
@section('meta_description', 'Bid on high-end film lighting and production gear in our timed auctions.')

@section('content')

<style>
.auc-hero {
    background: linear-gradient(135deg, #1e0e1a 0%, #2b1526 60%, #381c32 100%);
    padding: 3.5rem 0 2.5rem;
    position: relative; overflow: hidden;
}
.auc-hero::before {
    content: ''; position: absolute; top: -80px; right: -80px;
    width: 360px; height: 360px;
    background: radial-gradient(circle, rgba(236, 72, 153, .18) 0%, transparent 70%);
    border-radius: 50%;
}
.auc-hero-title { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; line-height: 1.15; }
.auc-hero-title span { color: #ec4899; }
.auc-hero-sub { color: rgba(255,255,255,.65); font-size: 1rem; margin-top: .5rem; }

.auc-body { padding: 2.5rem 0 4rem; background: #F5F4EF; }

.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.4rem; }

.product-card {
    background: #fff; border-radius: 16px;
    border: 1px solid #E5E4DF; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column;
    text-decoration: none; color: inherit;
}
.product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.12); border-color: #ec4899; }

.product-img { position: relative; height: 200px; overflow: hidden; background: #f5f5f5; }
.product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.product-card:hover .product-img img { transform: scale(1.04); }

.badge-auc {
    position: absolute; top: .6rem; left: .6rem;
    background: #ec4899; color: #fff;
    padding: .25rem .7rem; border-radius: 20px;
    font-size: .68rem; font-weight: 800; text-transform: uppercase;
}

.countdown-pill {
    position: absolute; bottom: .6rem; right: .6rem;
    background: rgba(0,0,0,.75); color: #fff; backdrop-filter: blur(4px);
    padding: .3rem .75rem; border-radius: 20px;
    font-size: .75rem; font-weight: 800; font-family: monospace;
}

.product-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
.product-cat { font-size: .72rem; color: #ec4899; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .3rem; }
.product-name { font-size: 1rem; font-weight: 700; color: #111; line-height: 1.35; margin-bottom: .5rem; flex: 1; }

.product-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; border-top: 1px solid #f5f4ef; padding-top: .7rem; }
.bid-lbl { font-size: .72rem; color: #888; text-transform: uppercase; font-weight: 700; }
.bid-val { font-size: 1.3rem; font-weight: 900; color: #ec4899; }

.btn-bid-now {
    background: #111; color: #fff; border: none;
    border-radius: 8px; padding: .5rem 1rem;
    font-size: .8rem; font-weight: 800;
}
.btn-bid-now:hover { background: #ec4899; }
</style>

<div class="auc-hero">
    <div class="container">
        <h1 class="auc-hero-title">Timed Equipment <span>Auctions</span></h1>
        <p class="auc-hero-sub">Bid on pro film equipment, cameras and grip gear before the deadline expires.</p>
    </div>
</div>

<div class="auc-body">
    <div class="container">
        @if($auctions->isEmpty())
            <div class="text-center p-5 bg-white rounded-4 border">
                <i class="bi bi-hammer" style="font-size:3rem;color:#ccc;display:block;margin-bottom:1rem;"></i>
                <h3 style="color:#555;">No active auctions at the moment</h3>
                <p class="text-muted">Check back soon for new equipment auction listings.</p>
            </div>
        @else
            <div class="products-grid">
                @foreach($auctions as $auction)
                <a href="{{ route('front.auctions.show', $auction->slug) }}" class="product-card">
                    <div class="product-img">
                        <img src="{{ $auction->primaryImageUrl() }}" alt="{{ $auction->title }}" loading="lazy">
                        <span class="badge-auc">Auction</span>
                        @if($auction->end_time)
                            <div class="countdown-pill" data-endtime="{{ $auction->end_time->toIso8601String() }}">Ending soon</div>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="product-cat">{{ $auction->category->name ?? 'Equipment' }}</div>
                        <div class="product-name">{{ Str::limit($auction->title, 55) }}</div>
                        <div style="font-size:.78rem;color:#888;margin-bottom:.5rem;">
                            <i class="bi bi-person-fill"></i> Vendor: {{ $auction->vendor->name }} · {{ $auction->bid_count }} bid(s)
                        </div>
                        <div class="product-footer">
                            <div>
                                <div class="bid-lbl">Current Bid</div>
                                <div class="bid-val">£{{ number_format($auction->current_bid, 2) }}</div>
                            </div>
                            <span class="btn-bid-now">Place Bid →</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div style="margin-top:2rem;display:flex;justify-content:center;">
                {{ $auctions->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateTimers() {
        document.querySelectorAll('.countdown-pill').forEach(el => {
            const end = new Date(el.dataset.endtime).getTime();
            const now = new Date().getTime();
            const diff = end - now;

            if (diff <= 0) {
                el.textContent = "ENDED";
                el.style.background = "rgba(220,38,38,.85)";
            } else {
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                el.textContent = `${hours}h ${mins}m ${secs}s`;
            }
        });
    }
    updateTimers();
    setInterval(updateTimers, 1000);
});
</script>

@endsection

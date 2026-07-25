@extends('front.layouts.app')

@section('title', 'Rent Film & Production Equipment | Light As Air Marketplace')
@section('meta_description', 'Rent pro film lighting, cameras, generators and grip equipment directly from verified vendors.')

@section('content')

<style>
.rental-hero {
    background: linear-gradient(135deg, #0e1e12 0%, #152b1b 60%, #1c3824 100%);
    padding: 3.5rem 0 2.5rem;
    position: relative;
    overflow: hidden;
}
.rental-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 360px; height: 360px;
    background: radial-gradient(circle, rgba(22, 163, 74, .18) 0%, transparent 70%);
    border-radius: 50%;
}
.rental-hero-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.15;
}
.rental-hero-title span { color: #22c55e; }
.rental-hero-sub { color: rgba(255,255,255,.65); font-size: 1rem; margin-top: .5rem; }

.search-bar-wrap {
    background: #fff;
    border-radius: 14px;
    padding: .5rem .5rem .5rem 1.2rem;
    display: flex; align-items: center; gap: .5rem;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    margin-top: 2rem;
}
.search-bar-wrap input {
    flex: 1; border: none; outline: none;
    font-size: 1rem; color: #111;
    background: transparent;
}
.search-bar-wrap button {
    background: #22c55e; color: #fff;
    border: none; border-radius: 10px;
    padding: .7rem 1.5rem; font-weight: 800;
    font-size: .9rem; cursor: pointer;
    transition: background .2s;
}
.search-bar-wrap button:hover { background: #16a34a; }

.rental-body { padding: 2.5rem 0 4rem; background: #F5F4EF; }
.rental-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
@media(max-width:900px) { .rental-layout { grid-template-columns: 1fr; } }

.filter-sidebar {
    background: #fff; border-radius: 16px;
    padding: 1.5rem; border: 1px solid #E5E4DF;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    position: sticky; top: 80px;
}
.filter-title {
    font-size: .78rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .07em; color: #888; margin-bottom: 1.2rem;
    padding-bottom: .7rem; border-bottom: 1px solid #f0ede8;
}
.filter-group { margin-bottom: 1.4rem; }
.filter-group label {
    font-size: .78rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #555; display: block; margin-bottom: .6rem;
}
.filter-select, .filter-input {
    width: 100%; padding: .6rem .85rem;
    border: 1.5px solid #e8e6e0; border-radius: 9px;
    font-size: .88rem; background: #fafafa;
    outline: none; transition: border-color .2s;
    font-family: inherit;
}
.filter-select:focus, .filter-input:focus { border-color: #22c55e; }
.price-row { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
.filter-apply {
    width: 100%; padding: .65rem;
    background: #22c55e; color: #fff; border: none; border-radius: 9px;
    font-weight: 800; font-size: .9rem; cursor: pointer;
    transition: background .2s; margin-top: .4rem;
}
.filter-apply:hover { background: #16a34a; }
.filter-reset {
    width: 100%; padding: .5rem; background: transparent;
    border: 1.5px solid #e8e6e0; border-radius: 9px;
    font-size: .82rem; color: #888; cursor: pointer;
    margin-top: .5rem; transition: all .2s;
    font-family: inherit; text-align: center; display: block; text-decoration: none;
}

.results-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem;
}
.results-count { font-size: .92rem; color: #666; font-weight: 600; }

.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.2rem; }

.product-card {
    background: #fff; border-radius: 14px;
    border: 1px solid #E5E4DF; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column;
    text-decoration: none; color: inherit;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.12);
    border-color: #22c55e;
}
.product-img {
    position: relative; height: 190px;
    overflow: hidden; background: #f5f5f5;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.product-card:hover .product-img img { transform: scale(1.04); }

.badge-type {
    position: absolute; top: .6rem; left: .6rem;
    background: #16a34a; color: #fff;
    padding: .25rem .7rem; border-radius: 20px;
    font-size: .68rem; font-weight: 800; letter-spacing: .04em;
    text-transform: uppercase;
}
.badge-featured {
    position: absolute; top: .6rem; right: .6rem;
    background: #FFC700; color: #111;
    padding: .25rem .65rem; border-radius: 20px;
    font-size: .65rem; font-weight: 800;
}

.product-body { padding: 1rem 1.1rem; flex: 1; display: flex; flex-direction: column; }
.product-cat { font-size: .72rem; color: #16a34a; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .3rem; }
.product-name { font-size: .95rem; font-weight: 700; color: #111; line-height: 1.35; margin-bottom: .5rem; flex: 1; }
.product-meta { font-size: .78rem; color: #888; margin-bottom: .6rem; display: flex; gap: .8rem; }
.product-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; border-top: 1px solid #f5f4ef; padding-top: .6rem; }
.product-price { font-size: 1.2rem; font-weight: 900; color: #16a34a; }
.product-price small { font-size: .75rem; font-weight: 500; color: #888; }
.btn-book {
    background: #111; color: #fff; border: none;
    border-radius: 8px; padding: .45rem .9rem;
    font-size: .78rem; font-weight: 700;
}
.btn-book:hover { background: #22c55e; }

.empty-state {
    text-align: center; padding: 4rem 2rem; color: #999;
    background: #fff; border-radius: 16px; border: 1px dashed #e0ddd6;
}
</style>

<div class="rental-hero">
    <div class="container">
        <h1 class="rental-hero-title">Rent Pro <span>Equipment</span><br>For Film & Production</h1>
        <p class="rental-hero-sub">Flexible daily and weekly rentals from verified vendors across the UK.</p>

        <form method="GET" action="{{ route('front.rentals') }}">
            <div class="search-bar-wrap" style="max-width:600px;">
                <i class="bi bi-search" style="color:#999;font-size:1.1rem;"></i>
                <input type="text" name="location" placeholder="Filter by location or city…" value="{{ request('location') }}">
                <button type="submit"><i class="bi bi-geo-alt-fill me-1"></i> Search Location</button>
            </div>
        </form>
    </div>
</div>

<div class="rental-body">
    <div class="container">
        <div class="rental-layout">

            {{-- FILTER SIDEBAR --}}
            <aside>
                <div class="filter-sidebar">
                    <div class="filter-title"><i class="bi bi-funnel-fill me-2"></i>Filter Rentals</div>
                    <form method="GET" action="{{ route('front.rentals') }}">

                        <div class="filter-group">
                            <label>Category</label>
                            <select name="category" class="filter-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Condition</label>
                            <select name="condition" class="filter-select">
                                <option value="">Any Condition</option>
                                <option value="new" @selected(request('condition') === 'new')>New</option>
                                <option value="used" @selected(request('condition') === 'used')>Used</option>
                                <option value="refurbished" @selected(request('condition') === 'refurbished')>Refurbished</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Max Daily Rate (£)</label>
                            <input type="number" name="max_price" class="filter-input" placeholder="e.g. 250" value="{{ request('max_price') }}">
                        </div>

                        <button type="submit" class="filter-apply"><i class="bi bi-check2 me-1"></i> Apply Filters</button>
                        <a href="{{ route('front.rentals') }}" class="filter-reset">Clear Filters</a>
                    </form>
                </div>
            </aside>

            {{-- RESULTS --}}
            <div>
                <div class="results-header">
                    <p class="results-count">
                        Showing <strong>{{ $listings->total() }}</strong> rental options available
                    </p>
                </div>

                @if($listings->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-calendar-x" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
                        <h3>No equipment available for rent</h3>
                        <p>Try clearing your filters or check back later.</p>
                    </div>
                @else
                    <div class="products-grid">
                        @foreach($listings as $listing)
                        <a href="{{ route('front.rentals.show', $listing->slug) }}" class="product-card">
                            <div class="product-img">
                                <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" loading="lazy">
                                <span class="badge-type">Rental</span>
                                @if($listing->is_featured)
                                    <span class="badge-featured">Featured</span>
                                @endif
                            </div>
                            <div class="product-body">
                                <div class="product-cat">{{ $listing->category->name ?? 'Equipment' }}</div>
                                <div class="product-name">{{ Str::limit($listing->title, 55) }}</div>
                                <div class="product-meta">
                                    <span><i class="bi bi-boxes"></i> {{ $listing->total_qty }} unit(s)</span>
                                    @if($listing->location)
                                        <span><i class="bi bi-geo-alt"></i> {{ $listing->location }}</span>
                                    @endif
                                </div>
                                <div class="product-footer">
                                    <div class="product-price">
                                        £{{ number_format($listing->price_per_day, 2) }}<small>/day</small>
                                    </div>
                                    <span class="btn-book">Check Dates</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <div style="margin-top:2rem;display:flex;justify-content:center;">
                        {{ $listings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection

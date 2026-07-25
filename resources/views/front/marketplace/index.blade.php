@extends('front.layouts.app')

@section('title', 'Marketplace — Buy Equipment | Light As Air')
@section('meta_description', 'Browse new and used film lighting, grip and camera equipment for sale on the Light As Air marketplace.')

@section('content')

<style>
/* ── MARKETPLACE BROWSE PAGE ──────────────────────────────── */
.mkt-hero {
    background: linear-gradient(135deg, #111 0%, #1a1a1a 60%, #222 100%);
    padding: 3.5rem 0 2.5rem;
    position: relative;
    overflow: hidden;
}
.mkt-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 360px; height: 360px;
    background: radial-gradient(circle, rgba(255,199,0,.12) 0%, transparent 70%);
    border-radius: 50%;
}
.mkt-hero-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.15;
}
.mkt-hero-title span { color: #FFC700; }
.mkt-hero-sub { color: rgba(255,255,255,.55); font-size: 1rem; margin-top: .5rem; }

/* ── SEARCH BAR ────────────────────────────────────────────── */
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
    background: #FFC700; color: #111;
    border: none; border-radius: 10px;
    padding: .7rem 1.5rem; font-weight: 800;
    font-size: .9rem; cursor: pointer;
    transition: background .2s;
}
.search-bar-wrap button:hover { background: #E6B200; }

/* ── BODY LAYOUT ──────────────────────────────────────────── */
.mkt-body { padding: 2.5rem 0 4rem; background: #F5F4EF; }
.mkt-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
@media(max-width:900px) { .mkt-layout { grid-template-columns: 1fr; } }

/* ── FILTER SIDEBAR ──────────────────────────────────────── */
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
.filter-select:focus, .filter-input:focus { border-color: #FFC700; }
.price-row { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
.filter-apply {
    width: 100%; padding: .65rem;
    background: #FFC700; border: none; border-radius: 9px;
    font-weight: 800; font-size: .9rem; cursor: pointer;
    transition: background .2s; margin-top: .4rem;
}
.filter-apply:hover { background: #E6B200; }
.filter-reset {
    width: 100%; padding: .5rem; background: transparent;
    border: 1.5px solid #e8e6e0; border-radius: 9px;
    font-size: .82rem; color: #888; cursor: pointer;
    margin-top: .5rem; transition: all .2s;
    font-family: inherit;
}
.filter-reset:hover { border-color: #ccc; color: #555; }

/* ── RESULTS HEADER ──────────────────────────────────────── */
.results-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem;
}
.results-count { font-size: .92rem; color: #666; font-weight: 600; }
.results-count strong { color: #111; }
.sort-select {
    padding: .5rem .85rem; border: 1.5px solid #e8e6e0;
    border-radius: 9px; font-size: .85rem; background: #fff;
    outline: none; font-family: inherit; cursor: pointer;
}

/* ── PRODUCT GRID ──────────────────────────────────────────── */
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
    border-color: #FFC700;
}
.product-img {
    position: relative; height: 200px;
    overflow: hidden; background: #f5f5f5;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.product-card:hover .product-img img { transform: scale(1.04); }

.badge-condition {
    position: absolute; top: .6rem; left: .6rem;
    padding: .25rem .7rem; border-radius: 20px;
    font-size: .68rem; font-weight: 800; letter-spacing: .04em;
    text-transform: uppercase;
}
.badge-new       { background: #dcfce7; color: #166534; }
.badge-used      { background: #fef3c7; color: #92400e; }
.badge-refurbished { background: #dbeafe; color: #1e40af; }

.badge-featured {
    position: absolute; top: .6rem; right: .6rem;
    background: #FFC700; color: #111;
    padding: .25rem .65rem; border-radius: 20px;
    font-size: .65rem; font-weight: 800;
}

.product-body { padding: 1rem 1.1rem; flex: 1; display: flex; flex-direction: column; }
.product-cat { font-size: .72rem; color: #FFC700; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .3rem; }
.product-name { font-size: .95rem; font-weight: 700; color: #111; line-height: 1.35; margin-bottom: .5rem; flex: 1; }
.product-seller { font-size: .75rem; color: #999; margin-bottom: .6rem; }
.product-seller i { margin-right: .25rem; }
.product-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.product-price { font-size: 1.25rem; font-weight: 900; color: #111; }
.product-price .currency { font-size: .85rem; font-weight: 700; }
.btn-view {
    background: #111; color: #FFC700; border: none;
    border-radius: 8px; padding: .45rem .9rem;
    font-size: .78rem; font-weight: 700;
    cursor: pointer; transition: all .2s;
    text-decoration: none;
}
.btn-view:hover { background: #FFC700; color: #111; }

/* ── EMPTY STATE ─────────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 4rem 2rem; color: #999;
    background: #fff; border-radius: 16px; border: 1px dashed #e0ddd6;
}
.empty-state i { font-size: 3.5rem; display: block; margin-bottom: 1rem; opacity: .5; }
.empty-state h3 { font-size: 1.2rem; color: #555; margin-bottom: .5rem; }

/* ── PAGINATION ───────────────────────────────────────────── */
.pagination-wrap { margin-top: 2rem; display: flex; justify-content: center; }
</style>

{{-- HERO --}}
<div class="mkt-hero">
    <div class="container">
        <h1 class="mkt-hero-title">Buy <span>Equipment</span><br>From Verified Vendors</h1>
        <p class="mkt-hero-sub">New & used film lighting, grip and camera equipment — all in one place.</p>

        <form method="GET" action="{{ url('/marketplace') }}">
            @if(request('category'))   <input type="hidden" name="category"   value="{{ request('category') }}">   @endif
            @if(request('condition'))  <input type="hidden" name="condition"  value="{{ request('condition') }}">  @endif
            @if(request('min_price'))  <input type="hidden" name="min_price"  value="{{ request('min_price') }}">  @endif
            @if(request('max_price'))  <input type="hidden" name="max_price"  value="{{ request('max_price') }}">  @endif
            @if(request('sort'))       <input type="hidden" name="sort"       value="{{ request('sort') }}">       @endif
            <div class="search-bar-wrap" style="max-width:600px;">
                <i class="bi bi-search" style="color:#999;font-size:1.1rem;"></i>
                <input type="text" name="search" placeholder="Search equipment, brand, keyword…" value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search me-1"></i> Search</button>
            </div>
        </form>
    </div>
</div>

{{-- BODY --}}
<div class="mkt-body">
    <div class="container">
        <div class="mkt-layout">

            {{-- FILTER SIDEBAR --}}
            <aside>
                <div class="filter-sidebar">
                    <div class="filter-title"><i class="bi bi-funnel-fill me-2"></i>Filters</div>
                    <form method="GET" action="{{ url('/marketplace') }}" id="filterForm">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

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
                                <option value="new"         @selected(request('condition') === 'new')>New</option>
                                <option value="used"        @selected(request('condition') === 'used')>Used</option>
                                <option value="refurbished" @selected(request('condition') === 'refurbished')>Refurbished</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range (£)</label>
                            <div class="price-row">
                                <input type="number" name="min_price" class="filter-input" placeholder="Min" min="0" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="filter-input" placeholder="Max" min="0" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Location</label>
                            <input type="text" name="location" class="filter-input" placeholder="City or postcode" value="{{ request('location') }}">
                        </div>

                        <button type="submit" class="filter-apply"><i class="bi bi-check2 me-1"></i> Apply Filters</button>
                        <a href="{{ url('/marketplace') }}" class="filter-reset d-block text-center">Clear All</a>
                    </form>
                </div>
            </aside>

            {{-- RESULTS --}}
            <div>
                <div class="results-header">
                    <p class="results-count">
                        <strong>{{ $products->total() }}</strong> listings found
                        @if(request('search')) for "<em>{{ request('search') }}</em>" @endif
                    </p>
                    <form method="GET" action="{{ url('/marketplace') }}">
                        @foreach(request()->except('sort') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="latest"     @selected(!request('sort') || request('sort') === 'latest')>Newest First</option>
                            <option value="price_asc"  @selected(request('sort') === 'price_asc')>Price: Low → High</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High → Low</option>
                            <option value="oldest"     @selected(request('sort') === 'oldest')>Oldest First</option>
                        </select>
                    </form>
                </div>

                @if($products->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-box-seam"></i>
                        <h3>No listings found</h3>
                        <p>Try adjusting your filters or <a href="{{ url('/marketplace') }}" style="color:#FFC700;font-weight:700;">browse all equipment</a>.</p>
                    </div>
                @else
                    <div class="products-grid">
                        @foreach($products as $product)
                        <a href="{{ url('/marketplace/' . $product->slug) }}" class="product-card">
                            <div class="product-img">
                                <img src="{{ $product->primaryImageUrl() }}" alt="{{ $product->title }}" loading="lazy">
                                <span class="badge-condition badge-{{ $product->condition }}">{{ ucfirst($product->condition) }}</span>
                                @if($product->is_featured)
                                    <span class="badge-featured"><i class="bi bi-star-fill"></i> Featured</span>
                                @endif
                            </div>
                            <div class="product-body">
                                <div class="product-cat">{{ $product->category->name ?? 'Equipment' }}</div>
                                <div class="product-name">{{ Str::limit($product->title, 60) }}</div>
                                <div class="product-seller"><i class="bi bi-shop"></i> {{ $product->seller->company_name ?? $product->seller->name }}</div>
                                <div class="product-footer">
                                    <div class="product-price"><span class="currency">£</span>{{ number_format($product->price, 2) }}</div>
                                    <span class="btn-view">View →</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <div class="pagination-wrap">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection

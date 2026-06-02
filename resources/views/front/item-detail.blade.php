{{-- resources/views/front/item-detail.blade.php --}}
@extends('front.layouts.app')

@section('content')

<style>
.page-header {
    background: linear-gradient(135deg, var(--dark) 0%, #2a2a2a 100%);
    padding: 2.5rem 0;
    margin-bottom: 2.5rem;
}
.page-header .breadcrumb-item,
.page-header .breadcrumb-item a {
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .82rem;
}
.page-header .breadcrumb-item.active { color: var(--brand); }

/* ── IMAGE PANEL ── */
.item-img-wrap {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 360px;
}
.item-img-wrap img {
    width: 100%;
    max-height: 380px;
    object-fit: cover;
    border-radius: 12px;
}

/* ── DETAIL PANEL ── */
.detail-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    padding: 2rem;
    height: 100%;
}
.item-title {
    font-size: 1.7rem;
    font-weight: 800;
    color: var(--dark);
    line-height: 1.2;
    margin-bottom: .6rem;
}
.item-desc {
    font-size: .92rem;
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}
.price-block {
    background: var(--brand-lt);
    border: 2px solid var(--brand);
    border-radius: 12px;
    padding: 1rem 1.4rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: baseline;
    gap: .5rem;
}
.price-amount {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
}
.price-label {
    font-size: .85rem;
    color: var(--muted);
    font-weight: 600;
}
.avail-row {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: 1.5rem;
    font-size: .88rem;
    color: var(--muted);
}
.avail-badge {
    background: #f0fdf4;
    border: 1.5px solid #86efac;
    color: #166534;
    font-weight: 700;
    font-size: .82rem;
    padding: .25rem .7rem;
    border-radius: 20px;
}

/* qty input */
.qty-wrap {
    display: flex;
    align-items: center;
    gap: 0;
    border: 2px solid var(--brand);
    border-radius: 10px;
    overflow: hidden;
    width: fit-content;
    margin-bottom: 1.5rem;
}
.qty-btn {
    background: var(--brand);
    border: none;
    color: var(--dark);
    font-size: 1.1rem;
    font-weight: 800;
    width: 40px;
    height: 42px;
    cursor: pointer;
    transition: background .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.qty-btn:hover { background: var(--brand-dk); }
.qty-input {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    width: 60px;
    text-align: center;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 0 !important;
    background: var(--white) !important;
}

/* add to cart btn */
.btn-add-cart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: var(--brand);
    color: var(--dark);
    font-weight: 800;
    font-size: 1rem;
    padding: .85rem 2rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
}
.btn-add-cart:hover {
    background: var(--brand-dk);
    color: var(--dark);
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
    transform: translateY(-1px);
}

/* feature pills */
.feature-list {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 1.5rem;
}
.feature-pill {
    background: #f8f8f8;
    border: 1.5px solid var(--border);
    border-radius: 20px;
    padding: .25rem .8rem;
    font-size: .78rem;
    font-weight: 600;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: .35rem;
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="/#items">Equipment</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($item->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">

    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
             style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;">
            <i class="bi bi-check-circle-fill fs-5" style="color:var(--success);"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4 align-items-start">

        <!-- Image -->
        <div class="col-lg-5">
            <div class="item-img-wrap">
                <img src="{{ asset('uploads/items/'.$item->image) }}" alt="{{ $item->title }}">
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-7">
            <div class="detail-card">
                <h1 class="item-title">{{ $item->title }}</h1>

                <div class="feature-list">
                    <span class="feature-pill"><i class="bi bi-check-circle-fill text-success"></i> Available Now</span>
                    <span class="feature-pill"><i class="bi bi-truck" style="color:var(--brand-dk);"></i> Free Delivery</span>
                    <span class="feature-pill"><i class="bi bi-arrow-counterclockwise text-primary"></i> Easy Returns</span>
                </div>

                <p class="item-desc">{{ $item->description }}</p>

                <div class="price-block">
                    <span class="price-amount">£{{ number_format($item->price_per_day, 2) }}</span>
                    <span class="price-label">per day</span>
                </div>

                <!-- <div class="avail-row">
                    <i class="bi bi-boxes fs-5"></i>
                    Available Quantity:
                    <span class="avail-badge">
                        <i class="bi bi-check-circle-fill me-1"></i>{{ $item->available_qty }} in stock
                    </span>
                </div> -->

                <form action="/add-to-cart" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                    <div style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--dark);margin-bottom:.5rem;">
                        Quantity
                    </div>

                    <div class="qty-wrap">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="number" name="qty" id="qtyInput" value="1" min="1"
                               max="{{ $item->available_qty }}" class="form-control qty-input">
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>

                    <button type="submit" class="btn-add-cart">
                        <i class="bi bi-cart-plus-fill"></i> Request to Product
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>

@endsection
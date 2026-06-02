@extends('front.layouts.app')

@section('content')
    <style>
        /* ── HERO CAROUSEL ── */
        .hero-section {
            position: relative;
        }

        .carousel-item {
            position: relative;
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 580px;
            object-fit: cover;
            filter: brightness(.55);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, .65) 0%, rgba(0, 0, 0, .3) 100%);
        }

        .hero-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            z-index: 2;
        }

        .hero-tag {
            display: inline-block;
            background: var(--brand);
            color: var(--dark);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: .35rem .9rem;
            border-radius: 30px;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero-title span {
            color: var(--brand);
        }

        .hero-sub {
            color: rgba(255, 255, 255, .8);
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 2rem;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-hero-primary {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            font-size: 1rem;
            padding: .75rem 2rem;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-hero-primary:hover {
            background: var(--brand-dk);
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 199, 0, .4);
        }

        .btn-hero-secondary {
            background: transparent;
            color: var(--white);
            font-weight: 600;
            font-size: 1rem;
            padding: .75rem 2rem;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, .4);
            text-decoration: none;
            transition: all .2s;
        }

        .btn-hero-secondary:hover {
            border-color: var(--brand);
            color: var(--brand);
        }

        /* carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, .15);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .2);
            margin: 0 1.5rem;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: var(--brand);
        }

        .carousel-indicators [data-bs-target] {
            background: rgba(255, 255, 255, .5);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: none;
        }

        .carousel-indicators .active {
            background: var(--brand);
            width: 24px;
            border-radius: 4px;
        }

        /* ── STATS STRIP ── */
        .stats-strip {
            background: var(--brand);
            padding: 1.5rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 0 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: var(--dark);
            line-height: 1;
        }

        .stat-label {
            font-size: .78rem;
            font-weight: 600;
            color: rgba(0, 0, 0, .6);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        /* ── CATEGORIES ── */
        .cat-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .cat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--brand);
        }

        .cat-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform .35s;
        }

        .cat-card:hover img {
            transform: scale(1.05);
        }

        .cat-card-body {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cat-card-body h6 {
            font-weight: 700;
            font-size: .93rem;
            color: var(--dark);
            margin: 0;
        }

        .cat-arrow {
            width: 30px;
            height: 30px;
            background: var(--brand-lt);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-size: .75rem;
            transition: background .2s;
        }

        .cat-card:hover .cat-arrow {
            background: var(--brand);
        }

        /* ── ITEM CARDS ── */
        .item-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform .25s, box-shadow .25s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .item-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .item-card .img-wrap {
            overflow: hidden;
            position: relative;
        }

        .item-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            transition: transform .4s;
        }

        .item-card:hover img {
            transform: scale(1.06);
        }

        .item-badge {
            position: absolute;
            top: .75rem;
            left: .75rem;
            background: var(--brand);
            color: var(--dark);
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .25rem .65rem;
            border-radius: 20px;
        }

        .item-card-body {
            padding: 1.1rem 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .item-title {
            font-size: .97rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: .4rem;
            line-height: 1.3;
        }

        .item-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
        }

        .item-price small {
            font-size: .75rem;
            font-weight: 500;
            color: var(--muted);
        }

        .qty-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #f0fdf4;
            color: var(--success);
            font-size: .75rem;
            font-weight: 600;
            padding: .25rem .65rem;
            border-radius: 20px;
            border: 1px solid #bbf7d0;
        }

        .item-card-footer {
            padding: .85rem 1.2rem;
            background: var(--light-bg);
            border-top: 1px solid var(--border);
            display: flex;
            gap: .6rem;
        }

        .btn-view {
            flex: 1;
            background: transparent;
            border: 1.5px solid var(--brand);
            color: var(--dark);
            font-weight: 600;
            font-size: .83rem;
            border-radius: 8px;
            padding: .5rem;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            transition: all .2s;
        }

        .btn-view:hover {
            background: var(--brand-lt);
            border-color: var(--brand-dk);
            color: var(--dark);
        }

        .btn-cart {
            flex: 1;
            background: var(--brand);
            border: none;
            color: var(--dark);
            font-weight: 700;
            font-size: .83rem;
            border-radius: 8px;
            padding: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-cart:hover {
            background: var(--brand-dk);
            box-shadow: 0 4px 14px rgba(255, 199, 0, .35);
        }

        /* ── HOW IT WORKS ── */
        .how-section {
            background: var(--light-bg);
        }

        .step-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .step-num {
            width: 60px;
            height: 60px;
            background: var(--brand);
            color: var(--dark);
            border-radius: 50%;
            font-size: 1.5rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .step-card h5 {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: .5rem;
        }

        .step-card p {
            color: var(--muted);
            font-size: .85rem;
            line-height: 1.6;
        }

        /* ── CTA ── */
        .cta-section {
            background: var(--brand);
            padding: 4rem 0;
        }
    </style>

    <div class="container py-5">

        <h2 class="mb-4">

            {{ $category->name }}

            Items

        </h2>

        <div class="row g-4">


            @foreach($items as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="item-card">
                        <div class="img-wrap">
                            <img src="{{ asset('uploads/items/' . $item->image) }}" alt="{{ $item->title }}">
                            <!-- <span class="item-badge"><i class="bi bi-check-circle-fill me-1"></i>Available</span> -->
                        </div>
                        <div class="item-card-body">
                            <div class="item-title">{{ $item->title }}</div>
                            <div class="item-price mt-1">
                                £{{ number_format($item->price_per_day, 2) }}
                                <small>/ day</small>
                            </div>
                            <!-- <div class="mt-2">
                                <span class="qty-badge">
                                    <i class="bi bi-boxes"></i> {{ $item->available_qty }} in stock
                                </span>
                            </div> -->
                        </div>
                        <div class="item-card-footer">
                            <a href="{{ url('item/' . $item->id) }}" class="btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            @if(Auth::check())
                                <form action="{{ url('/add-to-cart') }}" method="POST" style="flex:1;display:flex;">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn-cart w-100">
                                        <i class="bi bi-cart-plus-fill"></i> Request
                                    </button>
                                </form>
                            @else

                                <a href="{{ url('/login') }}">

                                    <button type="submit" class="btn-cart w-100">
                                        <i class="bi bi-cart-plus-fill"></i> Request
                                    </button>

                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        <div class="mt-4">

            {{ $items->links() }}

        </div>

    </div>

@endsection
{{-- resources/views/front/home.blade.php --}}
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

        .marquee-wrapper {
            width: 100%;
            background: var(--dark);
            overflow: hidden;
            padding: 12px 0;
            /* border-top: 2px solid #000; */
            /* border-bottom: 2px solid #000; */
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: scroll 20s linear infinite;
        }

        .marquee-text {
            color: var(--brand);
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            padding-right: 80px;
        }

        @keyframes scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .hero-sectiontext {
            background: var(--brand);
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-sectiontext h1 {
            color: #000;
            font-size: 10rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
        }

        .hero-sectiontext p {
            color: #000;
            font-size: 40px;
            font-weight: 400;
            max-width: 1000px;
            margin: 25px auto;
            line-height: 1.4;
        }

        .whatsapp-btn {
            background: #000;
            color: var(--brand);
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 22px;
            font-weight: 700;
            transition: .3s;
        }

        .whatsapp-btn:hover {
            background: #222;
            transform: translateY(-3px);
        }

        @media(max-width:768px) {

            .hero-section h1 {
                font-size: 42px;
            }

            .hero-sectiontext p {
                font-size: 20px;
            }

            .whatsapp-btn {
                font-size: 18px;
                padding: 15px 30px;
            }
        }
    </style>

    <!-- ── HERO CAROUSEL ── -->
    <section class="hero-section">
        <!-- <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500"> -->
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">

            <div class="carousel-indicators">
                @foreach($banners as $key => $banner)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ asset('uploads/banner/' . $banner->image) }}" alt="{{ $banner->title }}">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <span class="hero-tag"><i class="bi bi-lightning-charge-fill me-1"></i>Premium Rental Service</span>
                            <h1 class="hero-title">{{ $banner->title }}</h1>
                            <p class="hero-sub">Professional equipment rental with flexible terms. Book online in minutes,
                                delivered to your door.</p>
                            <div class="hero-actions">
                                <a href="#items" class="btn-hero-primary">
                                    <i class="bi bi-search"></i> Browse Equipment
                                </a>
                                <!-- <a href="/register" class="btn-hero-secondary">
                                            Get Started
                                        </a> -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <i class="bi bi-chevron-left" style="font-size:1.1rem;color:#fff;"></i>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <i class="bi bi-chevron-right" style="font-size:1.1rem;color:#fff;"></i>
            </button> -->
        </div>
    </section>


    <!-- ── STATS STRIP ── -->
    <section class="stats-strip">
        <div class="container">
            <div class="row g-3 justify-content-center text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Equipment Items</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">12k+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Customer Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CATEGORIES ── -->
    <section class="py-5 mt-2">
        <div class="container">

            <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <div class="section-label mb-1"><i class="bi bi-box-seam me-1"></i>Browse by Category</div>
                    <h2 class="section-title mb-0">What Are You Looking For?</h2>
                </div>
                <a href="{{ url('/categories') }}"
                    class="btn-brand-outline text-decoration-none d-flex align-items-center gap-1">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row g-3">
                @foreach($categories as $category)
                    <div class="col-6 col-md-3">
                        <a href="{{ url('/category/' . $category->id) }}" class="text-decoration-none text-dark">

                            <div class="cat-card">
                                <!-- <div style="overflow:hidden;">
                                                                <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->name }}">
                                                            </div> -->
                                <div class="cat-card-body">
                                    <h6>{{ $category->name }}</h6>
                                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ── RENTAL ITEMS ── -->
    <section class="py-5 bg-light" id="items">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <div class="section-label mb-1"><i class="bi bi-box-seam me-1"></i>Available Now</div>
                    <h2 class="section-title mb-0">Rental Equipment</h2>
                </div>
                <a href="/items" class="btn-brand-outline text-decoration-none d-flex align-items-center gap-1">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

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

                                                            <button class="btn-cart w-100" onclick="openRequestModal(
                                    '{{ $item->id }}',
                                    '{{ $item->title }}'
                                    )">
                                                                <i class="bi bi-cart-plus-fill"></i>
                                                                Request
                                                            </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── HOW IT WORKS ── -->
    <section class="how-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label mb-1"><i class="bi bi-info-circle-fill me-1"></i>Simple Process</div>
                <h2 class="section-title mb-0">How It Works</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-num"><i class="bi bi-search" style="font-size:1.3rem;"></i></div>
                        <h5>Browse & Select</h5>
                        <p>Explore our wide range of professional equipment by category or search.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-num"><i class="bi bi-cart-check-fill" style="font-size:1.3rem;"></i></div>
                        <h5>Add to Cart</h5>
                        <p>Select items and quantities, then add them to your cart in one click.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-num"><i class="bi bi-calendar2-check-fill" style="font-size:1.3rem;"></i></div>
                        <h5>Choose Dates</h5>
                        <p>Pick your rental start and end dates at checkout. Flexible options available.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-num"><i class="bi bi-truck" style="font-size:1.3rem;"></i></div>
                        <h5>Get It Delivered</h5>
                        <p>We deliver to your door and collect when your rental period ends.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            new bootstrap.Carousel(
                document.getElementById('heroCarousel'),
                {
                    interval: 3000,
                    pause: false,
                    wrap: true
                }
            );

        });
    </script>

    <div class="modal fade" id="requestModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Request Item</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" id="item_id">

<div class="mb-3">
    <label>Name *</label>
    <input type="text" id="name" class="form-control">
    <small class="text-danger" id="name_error"></small>
</div>

<div class="mb-3">
    <label>Email *</label>
    <input type="email" id="email" class="form-control">
    <small class="text-danger" id="email_error"></small>
</div>

<div class="mb-3">
    <label>Phone *</label>
    <input type="text" id="phone" class="form-control">
    <small class="text-danger" id="phone_error"></small>
</div>

<div class="mb-3">
    <label>Message</label>
    <textarea id="message" class="form-control"></textarea>
</div>

<button
class="btn btn-warning w-100"
onclick="submitRequest()">
Send Request
</button>

</div>

</div>
</div>
</div>
<script>

function openRequestModal(id,title)
{
    document.getElementById('item_id').value=id;

    new bootstrap.Modal(
        document.getElementById('requestModal')
    ).show();
}


</script>
<div class="position-fixed top-0 end-0 p-3" style="z-index:99999">

    <div id="liveToast"
         class="toast border-0 shadow">

        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Light As AIR</strong>
            <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="toast"></button>
        </div>

        <div class="toast-body" id="toastMessage">
        </div>

    </div>

</div>
<script>
    async function submitRequest()
{
    document.getElementById('name_error').innerHTML = '';
    document.getElementById('email_error').innerHTML = '';
    document.getElementById('phone_error').innerHTML = '';

    let name =
        document.getElementById('name').value.trim();

    let email =
        document.getElementById('email').value.trim();

    let phone =
        document.getElementById('phone').value.trim();

    let valid = true;

    if(!name)
    {
        document.getElementById('name_error')
        .innerHTML = 'Name is required';

        valid = false;
    }

    if(!email)
    {
        document.getElementById('email_error')
        .innerHTML = 'Email is required';

        valid = false;
    }
    else
    {
        let emailRegex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(!emailRegex.test(email))
        {
            document.getElementById('email_error')
            .innerHTML = 'Enter valid email';

            valid = false;
        }
    }

    if(!phone)
    {
        document.getElementById('phone_error')
        .innerHTML = 'Phone number is required';

        valid = false;
    }

    if(!valid)
    {
        return;
    }

    try{

        const response =
        await fetch('/guest-request',{

            method:'POST',

            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':document
                .querySelector('meta[name="csrf-token"]')
                .content
            },

            body:JSON.stringify({

                item_id:
                document.getElementById('item_id').value,

                name:name,
                email:email,
                phone:phone,

                message:
                document.getElementById('message').value
            })
        });

        const data = await response.json();

        if(!data.status)
        {
            showToast('Request failed.');
            return;
        }

        let msg =

`🔥 NEW LIGHT AS AIR REQUEST

Item: ${data.item_name}

Name: ${data.name}

Email: ${data.email}

Phone: ${data.phone}`;

        window.open(
            `https://wa.me/447879175585?text=${encodeURIComponent(msg)}`,
            '_blank'
        );

        bootstrap.Modal
        .getInstance(
            document.getElementById('requestModal')
        ).hide();

        document.getElementById('name').value='';
        document.getElementById('email').value='';
        document.getElementById('phone').value='';
        document.getElementById('message').value='';

        showToast(
            '✅ Request submitted successfully. WhatsApp opened.'
        );

    }
    catch(error)
    {
        console.log(error);

        showToast(
            '❌ Something went wrong. Please try again.'
        );
    }
}
</script>


@endsection
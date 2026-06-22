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
            color: rgba(255, 255, 255, .5);
            text-decoration: none;
            font-size: .82rem;
        }

        .page-header .breadcrumb-item.active {
            color: var(--brand);
        }

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

        .qty-btn:hover {
            background: var(--brand-dk);
        }

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
            box-shadow: 0 6px 20px rgba(255, 199, 0, .4);
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

        .btn-hero-primary {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            font-size: 2rem;
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

        .product-image {
            cursor: pointer;
            transition: 0.3s;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .image-modal .modal-dialog {
            max-width: 100vw;
            margin: 0;
        }

        .image-modal .modal-content {
            background: #000;
            border: none;
            border-radius: 0;
            height: 100vh;
        }

        .image-modal .modal-body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .image-modal img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .image-close {
            position: absolute;
            top: 15px;
            right: 20px;
            z-index: 9999;
            font-size: 35px;
            color: #fff;
            background: none;
            border: none;
            cursor: pointer;
        }

        .product-gallery {
            display: flex;
            gap: 15px;
        }

        .thumb-gallery {
            width: 90px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .thumb-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid #eee;
            border-radius: 8px;
            cursor: pointer;
            transition: .3s;
        }

        .thumb-image:hover {
            border-color: #ffc700;
        }

        .main-gallery-image {
            flex: 1;
        }

        .main-gallery-image img {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
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
                    @php

                        $images = $item->image ?? [];

                        if (!is_array($images)) {
                            $images = [$images];
                        }

                        $mainImage = $images[0] ?? '';

                    @endphp

                    <div class="product-gallery">

                        <div class="thumb-gallery">

                            @foreach($images as $img)

                                <img src="{{ asset('uploads/items/' . $img) }}" class="thumb-image" onclick="changeImage(this)"
                                    data-full="{{ asset('uploads/items/' . $img) }}">

                            @endforeach

                        </div>

                        <div class="main-gallery-image">

                            <img id="mainProductImage" src="{{ asset('uploads/items/' . $mainImage) }}"
                                alt="{{ $item->title }}" class="product-image" data-bs-toggle="modal"
                                data-bs-target="#imageModal">

                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade image-modal" id="imageModal" tabindex="-1">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">

                        <button type="button" class="image-close" data-bs-dismiss="modal">
                            &times;
                        </button>

                        <div class="modal-body">
                            <img id="modalProductImage" src="{{ asset('uploads/items/' . $mainImage) }}">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-7">
                <div class="detail-card">
                    <h1 class="item-title">{{ $item->title }}</h1>

                    <div class="feature-list">
                        <span class="feature-pill"><i class="bi bi-check-circle-fill text-success"></i> Available Now</span>
                        <!-- <span class="feature-pill"><i class="bi bi-truck" style="color:var(--brand-dk);"></i> Free
                                        Delivery</span>
                                    <span class="feature-pill"><i class="bi bi-arrow-counterclockwise text-primary"></i> Easy
                                        Returns</span> -->
                    </div>

                    <!-- <p class="item-desc">{{ $item->description }}</p> -->
                    <p class="item-desc">{!! $item->description !!}</p>

                    <!-- <div class="price-block">
                                    <span class="price-amount">£{{ number_format($item->price_per_day, 2) }}</span>
                                    <span class="price-label">per day</span>
                                </div> -->

                    <!-- <div class="avail-row">
                                    <i class="bi bi-boxes fs-5"></i>
                                    Available Quantity:
                                    <span class="avail-badge">
                                        <i class="bi bi-check-circle-fill me-1"></i>{{ $item->available_qty }} in stock
                                    </span>
                                </div> -->
                    @if(Auth::check())
                        <form action="/add-to-cart" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            <div
                                style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--dark);margin-bottom:.5rem;">
                                Quantity
                            </div>

                            <div class="qty-wrap">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                <input type="number" name="qty" id="qtyInput" value="1" min="1" max="{{ $item->available_qty }}"
                                    class="form-control qty-input">
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                            </div>

                            <button type="submit" class="btn-add-cart">
                                <i class="bi bi-cart-plus-fill"></i> Request to Product
                            </button>
                        </form>

                    @else

                        <!-- <button class="btn-add-cart" onclick="openRequestModal(
                                                                    '{{ $item->id }}',
                                                                    '{{ $item->title }}'
                                                                    )">
                                                                                                <i class="bi bi-cart-plus-fill"></i>
                                                                                                Request
                                                                                            </button> -->
                    @endif
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

                    <button class="btn btn-warning w-100" onclick="submitRequest()">
                        Send Request
                    </button>

                </div>

            </div>
        </div>
    </div>
    <script>

        function openRequestModal(id, title) {
            document.getElementById('item_id').value = id;

            new bootstrap.Modal(
                document.getElementById('requestModal')
            ).show();
        }


    </script>
    <div class="position-fixed top-0 end-0 p-3" style="z-index:99999">

        <div id="liveToast" class="toast border-0 shadow">

            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Light As AIR</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>

            <div class="toast-body" id="toastMessage">
            </div>

        </div>

    </div>
    <script>
        async function submitRequest() {
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

            if (!name) {
                document.getElementById('name_error')
                    .innerHTML = 'Name is required';

                valid = false;
            }

            if (!email) {
                document.getElementById('email_error')
                    .innerHTML = 'Email is required';

                valid = false;
            }
            else {
                let emailRegex =
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(email)) {
                    document.getElementById('email_error')
                        .innerHTML = 'Enter valid email';

                    valid = false;
                }
            }

            if (!phone) {
                document.getElementById('phone_error')
                    .innerHTML = 'Phone number is required';

                valid = false;
            }

            if (!valid) {
                return;
            }

            try {

                const response =
                    await fetch('/guest-request', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .content
                        },

                        body: JSON.stringify({

                            item_id:
                                document.getElementById('item_id').value,

                            name: name,
                            email: email,
                            phone: phone,

                            message:
                                document.getElementById('message').value
                        })
                    });

                const data = await response.json();

                if (!data.status) {
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

                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('phone').value = '';
                document.getElementById('message').value = '';

                showToast(
                    '✅ Request submitted successfully. WhatsApp opened.'
                );

            }
            catch (error) {
                console.log(error);

                showToast(
                    '❌ Something went wrong. Please try again.'
                );
            }
        }
    </script>
    <script>

        function changeImage(el) {
            let image = el.getAttribute('data-full');

            document.getElementById('mainProductImage').src = image;

            document.getElementById('modalProductImage').src = image;
        }

    </script>

@endsection
{{-- resources/views/front/checkout.blade.php --}}
@extends('front.layouts.app')

@section('content')

    <style>
        .page-header {
            background: linear-gradient(135deg, var(--dark) 0%, #2a2a2a 100%);
            padding: 2.5rem 0;
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--white);
            margin: 0;
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

        /* ── FORM CARD ── */
        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .form-card-header {
            background: var(--dark);
            color: var(--white);
            padding: 1.1rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-card-body {
            padding: 1.8rem 1.5rem;
        }

        .form-label {
            font-size: .82rem;
            font-weight: 700;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .4rem;
        }

        .form-control {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: .65rem .9rem;
            font-size: .9rem;
            color: var(--dark);
            transition: border-color .2s, box-shadow .2s;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(255, 199, 0, .18);
            outline: none;
            background: var(--white);
        }

        /* date/time row highlight */
        .date-row {
            background: var(--brand-lt);
            border: 1.5px solid var(--brand);
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.4rem;
        }

        .date-row-label {
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--brand-dk);
            margin-bottom: .8rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        /* ── SUMMARY CARD ── */
        .summary-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .summary-header {
            background: var(--dark);
            color: var(--white);
            padding: 1.1rem 1.4rem;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .summary-body {
            padding: 1.4rem;
        }

        .summary-item {
            display: flex;
            gap: .9rem;
            padding: .85rem 0;
            border-bottom: 1px solid var(--border);
            align-items: center;
        }

        .summary-item:last-of-type {
            border-bottom: none;
        }

        .summary-item-img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            flex-shrink: 0;
        }

        .summary-item-name {
            font-weight: 600;
            font-size: .85rem;
            color: var(--dark);
            line-height: 1.3;
        }

        .summary-item-meta {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .15rem;
        }

        .summary-item-price {
            margin-left: auto;
            font-weight: 700;
            font-size: .88rem;
            white-space: nowrap;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            font-size: .88rem;
            color: var(--muted);
        }

        .calc-row strong {
            color: var(--dark);
        }

        .grand-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .9rem 1.4rem;
            background: var(--brand-lt);
            border-top: 2px solid var(--brand);
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--dark);
        }

        .btn-place-order {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            background: var(--brand);
            color: var(--dark);
            font-weight: 800;
            font-size: 1rem;
            padding: .9rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all .2s;
            width: 100%;
            margin-top: 1.2rem;
        }

        .btn-place-order:hover {
            background: var(--brand-dk);
            box-shadow: 0 6px 20px rgba(255, 199, 0, .4);
            transform: translateY(-1px);
        }

        /* duration badge */
        .duration-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--dark);
            color: var(--brand);
            font-size: .78rem;
            font-weight: 700;
            padding: .3rem .75rem;
            border-radius: 20px;
            margin-bottom: .8rem;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
            <h1><i class="bi bi-calendar2-check me-2" style="color:var(--brand);"></i>Rental Booking</h1>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4">

            <!-- Booking Form -->
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-calendar3"></i> Rental Booking Information
                    </div>
                    <div class="form-card-body">
                        <form id="bookingForm">
                            @csrf

                            <!-- Dates -->
                            <div class="date-row">
                                <div class="date-row-label"><i class="bi bi-calendar-range-fill"></i> Rental Period</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Times -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-clock me-1"></i>Start Time</label>
                                    <input type="time" name="start_time" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-clock-history me-1"></i>End Time</label>
                                    <input type="time" name="end_time" class="form-control" required>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-pencil-square me-1"></i>Special Note</label>
                                <textarea name="note" class="form-control" rows="4"
                                    placeholder="Any special requirements, delivery instructions..."></textarea>
                            </div>

                            <!-- Trust row -->
                            <div class="d-flex flex-wrap gap-3 mb-4">
                                <div class="d-flex align-items-center gap-2" style="font-size:.78rem;color:var(--muted);">
                                    <i class="bi bi-shield-fill-check text-success"></i> Secure Booking
                                </div>
                                <!-- <div class="d-flex align-items-center gap-2" style="font-size:.78rem;color:var(--muted);">
                                    <i class="bi bi-truck" style="color:var(--brand-dk);"></i> Free Delivery
                                </div>
                                <div class="d-flex align-items-center gap-2" style="font-size:.78rem;color:var(--muted);">
                                    <i class="bi bi-arrow-counterclockwise text-primary"></i> Easy Returns
                                </div> -->
                            </div>

                            <button type="button" class="btn-place-order" onclick="placeOrder()">
                                <i class="bi bi-lock-fill"></i> Place Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <div class="summary-header">
                        <i class="bi bi-receipt-cutoff"></i> Order Summary
                    </div>
                    <div class="summary-body">

                        @php $perDayTotal = 0; @endphp

                        @foreach(session('cart', []) as $row)
                            @php
                                $itemTotal = $row['price'] * $row['qty'];
                                $perDayTotal += $itemTotal;
                            @endphp
                            <div class="summary-item">
                                @if(isset($row['image']))
                                    <img src="{{ asset('uploads/items/' . $row['image']) }}" class="summary-item-img"
                                        alt="{{ $row['title'] }}">
                                @else
                                    <div class="summary-item-img d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div style="flex:1;min-width:0;">
                                    <div class="summary-item-name">{{ Str::limit($row['title'], 24) }}</div>
                                    <div class="summary-item-meta">Qty: {{ $row['qty'] }} &bull;
                                        £{{ number_format($row['price'], 2) }}/day</div>
                                </div>
                                <div class="summary-item-price">£{{ number_format($itemTotal, 2) }}</div>
                            </div>
                        @endforeach

                        <div class="pt-3 pb-1">
                            <div class="calc-row">
                                <span>Per Day Total</span>
                                <strong>£<span id="per_day_total">{{ number_format($perDayTotal, 2) }}</span></strong>
                            </div>
                            <div class="calc-row">
                                <span>Rental Days</span>
                                <strong><span id="days">1</span> day(s)</strong>
                            </div>
                            <div class="calc-row">
                                <span>Delivery</span>
                                <strong style="color:var(--success);"><i
                                        class="bi bi-check-circle-fill me-1"></i>Free</strong>
                            </div>
                        </div>
                    </div>

                    <div class="grand-total-row">
                        <span>Grand Total</span>
                        <span>£<span id="grand_total">{{ number_format($perDayTotal, 2) }}</span></span>
                    </div>
                </div>

                <p class="text-center mt-3" style="font-size:.75rem;color:var(--muted);">
                    * Final total calculated based on selected rental duration
                </p>
            </div>

        </div>
    </div>

    <script>
        let perDayTotal = {{ $perDayTotal }};

        function calculateTotal() {
            let start = document.getElementById('start_date').value;
            let end = document.getElementById('end_date').value;
            if (start && end) {
                let diff = Math.ceil((new Date(end) - new Date(start)) / (1000 * 60 * 60 * 24)) + 1;
                if (diff < 1) diff = 1;
                document.getElementById('days').innerHTML = diff;
                document.getElementById('grand_total').innerHTML = (perDayTotal * diff).toFixed(2);
            }
        }

        document.getElementById('start_date').addEventListener('change', calculateTotal);
        document.getElementById('end_date').addEventListener('change', calculateTotal);

        async function placeOrder() {

            try {

                let start_date = document.getElementById('start_date').value;
                let end_date = document.getElementById('end_date').value;

                if (!start_date || !end_date) {
                    alert('Please select Start Date and End Date');
                    return;
                }

                if (new Date(end_date) < new Date(start_date)) {
                    alert('End Date must be greater than or equal to Start Date');
                    return;
                }

                const response = await fetch('/place-order', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },

                    body: JSON.stringify({
                        start_date: start_date,
                        end_date: end_date
                    })

                });

                const data = await response.json();

                console.log(data);

                if (!data.status) {
                    alert(data.message);
                    return;
                }

                let msg =
                    `🛒 NEW RENTAL BOOKING

    Booking No: ${data.booking_no}

    Total Amount: £${data.total}

    `;

                data.items.forEach(item => {

                    msg +=
                        `${item.title}
    Qty: ${item.qty}
    Price: £${item.price}

    `;

                });

                window.open(
                    `https://wa.me/447879175585?text=${encodeURIComponent(msg)}`,
                    '_blank'
                );

                setTimeout(() => {
                    window.location.href = '/my-bookings';
                }, 1000);

            }
            catch (error) {

                console.log(error);

                alert('Something went wrong');

            }
        }
    </script>

@endsection
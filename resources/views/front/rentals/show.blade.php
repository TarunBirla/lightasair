@extends('front.layouts.app')

@section('title', $rental->title . ' — Hire Equipment')
@section('meta_description', Str::limit(strip_tags($rental->description), 155))

@section('content')

<style>
.rd-breadcrumb { background: #f5f4ef; padding: .85rem 0; border-bottom: 1px solid #e8e6de; font-size: .8rem; color: #888; }
.rd-breadcrumb a { color: #888; text-decoration: none; }
.rd-breadcrumb a:hover { color: #16a34a; }
.rd-breadcrumb span { margin: 0 .4rem; }

.rd-section { background: #F5F4EF; padding: 2.5rem 0 4rem; }

.rd-grid { display: grid; grid-template-columns: 1fr 420px; gap: 2rem; align-items: start; }
@media(max-width: 960px) { .rd-grid { grid-template-columns: 1fr; } }

.rd-gallery { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; overflow: hidden; }
.rd-main-img { width: 100%; height: 420px; object-fit: contain; background: #f8f8f6; display: block; }
.rd-thumbs { display: flex; gap: .6rem; padding: 1rem; overflow-x: auto; background: #fafaf8; border-top: 1px solid #f0ede8; }
.rd-thumb { width: 80px; height: 68px; flex-shrink: 0; object-fit: cover; border-radius: 8px; border: 2px solid transparent; cursor: pointer; }
.rd-thumb.active { border-color: #16a34a; }

.rd-book-card { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 1.6rem; position: sticky; top: 80px; }
.rd-title { font-size: 1.4rem; font-weight: 900; color: #111; line-height: 1.25; margin-bottom: .4rem; }

.rd-price-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 1rem; margin-bottom: 1.2rem; }
.rd-price { font-size: 2rem; font-weight: 900; color: #166534; line-height: 1; }
.rd-price small { font-size: .85rem; font-weight: 500; color: #6b7280; }

.form-label { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #555; display: block; margin-bottom: .4rem; }
.rd-input { width: 100%; padding: .65rem .85rem; border: 1.5px solid #e8e6e0; border-radius: 9px; font-size: .9rem; background: #fafafa; font-family: inherit; margin-bottom: 1rem; }
.rd-input:focus { outline: none; border-color: #16a34a; background: #fff; }

.calc-summary { background: #fafafa; border: 1px solid #eee; border-radius: 10px; padding: .85rem 1rem; margin-bottom: 1.2rem; font-size: .88rem; }
.calc-row { display: flex; justify-content: space-between; margin-bottom: .4rem; color: #666; }
.calc-row.total { border-top: 1px solid #e5e7eb; padding-top: .5rem; margin-top: .4rem; font-weight: 900; font-size: 1.05rem; color: #111; }

.btn-book-now {
    width: 100%; padding: 1rem; border: none; border-radius: 12px;
    background: #16a34a; color: #fff; font-weight: 900; font-size: 1rem;
    cursor: pointer; transition: background .2s;
}
.btn-book-now:hover { background: #15803d; }

.rd-desc { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 1.8rem; margin-top: 1.5rem; }
.rd-desc h2 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; padding-bottom: .4rem; border-bottom: 2px solid #22c55e; display: inline-block; }
</style>

<div class="rd-breadcrumb">
    <div class="container">
        <a href="/">Home</a><span>/</span>
        <a href="{{ route('front.rentals') }}">Rental Marketplace</a><span>/</span>
        {{ Str::limit($rental->title, 45) }}
    </div>
</div>

<div class="rd-section">
    <div class="container">
        <div class="rd-grid">

            <div>
                {{-- Gallery --}}
                <div class="rd-gallery">
                    @if($rental->images && count($rental->images))
                        <img src="{{ asset('storage/' . $rental->images[0]) }}" id="rdMainImg" class="rd-main-img" alt="{{ $rental->title }}">
                        @if(count($rental->images) > 1)
                        <div class="rd-thumbs">
                            @foreach($rental->images as $i => $img)
                            <img src="{{ asset('storage/' . $img) }}" class="rd-thumb {{ $i === 0 ? 'active' : '' }}"
                                 onclick="document.getElementById('rdMainImg').src=this.src; document.querySelectorAll('.rd-thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active')">
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
                <div class="rd-desc">
                    <h2>Equipment Description</h2>
                    <div style="font-size:.92rem;line-height:1.8;color:#444;white-space:pre-line;">{{ $rental->description }}</div>
                </div>
            </div>

            {{-- Booking Form --}}
            <div>
                <div class="rd-book-card">
                    <h1 class="rd-title">{{ $rental->title }}</h1>
                    <p style="font-size:.85rem;color:#888;margin-bottom:1rem;"><i class="bi bi-shop me-1"></i> Owner: {{ $rental->vendor->name }}</p>

                    <div class="rd-price-box">
                        <div class="rd-price">£{{ number_format($rental->price_per_day, 2) }}<small>/day</small></div>
                        @if($rental->price_per_week)
                            <div style="font-size:.82rem;color:#166534;margin-top:.2rem;">Weekly Rate: £{{ number_format($rental->price_per_week, 2) }}/week</div>
                        @endif
                        @if($rental->deposit_amount > 0)
                            <div style="font-size:.82rem;color:#65a30d;margin-top:.2rem;"><i class="bi bi-shield-lock me-1"></i> Refundable Deposit: £{{ number_format($rental->deposit_amount, 2) }}</div>
                        @endif
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-3" style="font-size:.85rem;border-radius:10px;">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('front.rentals.book', $rental->id) }}" id="bookingForm">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
                            <div>
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="startDate" class="rd-input" min="{{ date('Y-m-d') }}" required onchange="calculateTotal()">
                            </div>
                            <div>
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" id="endDate" class="rd-input" min="{{ date('Y-m-d') }}" required onchange="calculateTotal()">
                            </div>
                        </div>

                        <label class="form-label">Quantity (Max {{ $rental->total_qty }})</label>
                        <input type="number" name="qty" id="qtyInput" class="rd-input" value="1" min="1" max="{{ $rental->total_qty }}" required onchange="calculateTotal()">

                        @if($rental->offers_delivery)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="requires_delivery" id="deliveryCheck" value="1" onchange="calculateTotal()">
                                <label class="form-check-label" for="deliveryCheck" style="font-size:.85rem;">
                                    Include Delivery (£{{ number_format($rental->delivery_fee, 2) }})
                                </label>
                            </div>
                            <div id="deliveryAddressWrap" style="display:none;">
                                <label class="form-label">Delivery Address</label>
                                <textarea name="delivery_address" class="rd-input" style="min-height:60px;" placeholder="Full delivery address & instructions"></textarea>
                            </div>
                        @endif

                        <div class="calc-summary" id="calcSummary">
                            <div class="calc-row"><span>Rental Duration:</span><span id="summaryDays">1 day(s)</span></div>
                            <div class="calc-row"><span>Rental Subtotal:</span><span id="summarySubtotal">£{{ number_format($rental->price_per_day, 2) }}</span></div>
                            @if($rental->deposit_amount > 0)
                                <div class="calc-row"><span>Security Deposit:</span><span>£{{ number_format($rental->deposit_amount, 2) }}</span></div>
                            @endif
                            <div class="calc-row total"><span>Total Payable:</span><span id="summaryTotal">£{{ number_format($rental->price_per_day + $rental->deposit_amount, 2) }}</span></div>
                        </div>

                        @auth
                            <button type="submit" class="btn-book-now">
                                <i class="bi bi-calendar-check-fill me-2"></i> Submit Booking Request
                            </button>
                        @else
                            <a href="/login" class="btn-book-now" style="display:block;text-align:center;text-decoration:none;">
                                Log in to Request Booking
                            </a>
                        @endauth
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const startStr = document.getElementById('startDate').value;
    const endStr = document.getElementById('endDate').value;
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const deliveryCheck = document.getElementById('deliveryCheck');

    if (deliveryCheck) {
        document.getElementById('deliveryAddressWrap').style.display = deliveryCheck.checked ? 'block' : 'none';
    }

    if (!startStr || !endStr) return;

    const start = new Date(startStr);
    const end = new Date(endStr);
    const timeDiff = end.getTime() - start.getTime();
    if (timeDiff < 0) return;

    const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
    const ratePerDay = {{ $rental->price_per_day }};
    const deposit = {{ $rental->deposit_amount ?? 0 }};
    const deliveryFee = (deliveryCheck && deliveryCheck.checked) ? {{ $rental->delivery_fee ?? 0 }} : 0;

    const subtotal = ratePerDay * days * qty;
    const total = subtotal + deposit + deliveryFee;

    document.getElementById('summaryDays').textContent = days + ' day(s)';
    document.getElementById('summarySubtotal').textContent = '£' + subtotal.toFixed(2);
    document.getElementById('summaryTotal').textContent = '£' + total.toFixed(2);
}
</script>

@endsection

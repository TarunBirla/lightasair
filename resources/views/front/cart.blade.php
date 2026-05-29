{{-- resources/views/front/cart.blade.php --}}
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
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .82rem;
}
.page-header .breadcrumb-item.active { color: var(--brand); }
.page-header .breadcrumb-divider { color: rgba(255,255,255,.3); }

/* ── CART TABLE ── */
.cart-wrap {
    /* background: var(--white); */
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    /* border: 1px solid var(--border); */
    /* overflow: hidden; */
}
.cart-table { margin: 0; }
.cart-table thead th {
    background: var(--dark);
    color: var(--white);
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 1rem 1.2rem;
    border: none;
}
.cart-table tbody td {
    padding: 1.1rem 1.2rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
    font-size: .9rem;
    color: var(--dark);
}
.cart-table tbody tr:last-child td { border-bottom: none; }
.cart-table tbody tr { transition: background .15s; }
.cart-table tbody tr:hover td { background: #fffdf0; }

.cart-item-img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid var(--border);
}
.item-name-cell { font-weight: 600; font-size: .93rem; }
.qty-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--brand-lt);
    border: 1.5px solid var(--brand);
    color: var(--dark);
    font-weight: 700;
    font-size: .85rem;
    width: 36px;
    height: 36px;
    border-radius: 8px;
}
.price-cell { font-weight: 600; color: var(--muted); }
.total-cell { font-weight: 700; color: var(--dark); font-size: 1rem; }
.btn-remove {
    background: #fff1f2;
    border: 1.5px solid #fecdd3;
    color: var(--danger);
    font-size: .8rem;
    font-weight: 600;
    padding: .4rem .85rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: .35rem;
    text-decoration: none;
    white-space: nowrap;
}
.btn-remove:hover {
    background: var(--danger);
    color: var(--white);
    border-color: var(--danger);
}

/* ── TOTAL ROW ── */
.total-row td {
    background: var(--brand-lt) !important;
    border-top: 2px solid var(--brand) !important;
    font-weight: 800 !important;
    font-size: 1.1rem !important;
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
.summary-body { padding: 1.4rem; }
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .6rem 0;
    border-bottom: 1px solid var(--border);
    font-size: .88rem;
}
.summary-row:last-child { border-bottom: none; }
.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .8rem 0 0;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--dark);
}
.btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: var(--brand);
    color: var(--dark);
    font-weight: 800;
    font-size: 1rem;
    padding: .85rem;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .2s;
    width: 100%;
    margin-top: 1.2rem;
}
.btn-checkout:hover {
    background: var(--brand-dk);
    color: var(--dark);
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
    transform: translateY(-1px);
}
.btn-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: transparent;
    color: var(--muted);
    font-weight: 500;
    font-size: .85rem;
    padding: .6rem;
    border-radius: 8px;
    text-decoration: none;
    border: 1.5px solid var(--border);
    transition: all .2s;
    width: 100%;
    margin-top: .6rem;
}
.btn-continue:hover {
    border-color: var(--brand);
    color: var(--dark);
}

/* ── EMPTY CART ── */
.empty-cart {
    text-align: center;
    padding: 5rem 2rem;
}
.empty-icon {
    width: 90px;
    height: 90px;
    background: var(--brand-lt);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--brand-dk);
    margin: 0 auto 1.5rem;
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item active">Shopping Cart</li>
            </ol>
        </nav>
        <h1><i class="bi bi-cart3 me-2" style="color:var(--brand);"></i>Shopping Cart</h1>
    </div>
</div>

<div class="container pb-5">

    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;">
            <i class="bi bi-check-circle-fill fs-5" style="color:var(--success);"></i>
            {{ session('success') }}
        </div>
    @endif

    @php $cartItems = session('cart', []); $total = 0; @endphp

    @if(count($cartItems) > 0)
        <div class="row g-4">
            <!-- Cart Table -->
            <div class="col-lg-8">
                <div class="cart-wrap">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Price/Day</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $row)
                                @php
                                    $lineTotal = $row['price'] * $row['qty'];
                                    $total += $lineTotal;
                                @endphp
                                <tr>
                                    <td>
                                        @if(isset($row['image']))
                                            <img src="{{ asset('uploads/items/'.$row['image']) }}"
                                                 class="cart-item-img" alt="{{ $row['title'] }}">
                                        @else
                                            <div class="cart-item-img d-flex align-items-center justify-content-center bg-light text-muted">
                                                <i class="bi bi-image fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="item-name-cell">{{ $row['title'] }}</div>
                                        <div style="font-size:.77rem;color:var(--muted);margin-top:.2rem;">
                                            ID #{{ $row['item_id'] }}
                                        </div>
                                    </td>
                                    <td>

<div
style="
display:flex;
align-items:center;
gap:8px;
">

<a
href="{{ route('cart.decrease',$row['item_id']) }}"
class="btn btn-sm btn-warning">

-

</a>

<span class="qty-pill">

{{ $row['qty'] }}

</span>

<a
href="{{ route('cart.increase',$row['item_id']) }}"
class="btn btn-sm btn-warning">

+

</a>

</div>

</td>
                                    <td class="price-cell">£{{ number_format($row['price'], 2) }}</td>
                                    <td class="total-cell">£{{ number_format($lineTotal, 2) }}</td>
                                    <td>
                                        <a href="{{ route('cart.remove', $row['item_id']) }}"
                                           class="btn-remove"
                                           onclick="return confirm('Remove this item from cart?')">
                                            <i class="bi bi-trash3-fill"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="4" class="fw-bold">
                                    <i class="bi bi-receipt me-2"></i>Grand Total (per day)
                                </td>
                                <td class="fw-bold" style="font-size:1.15rem;">
                                    £{{ number_format($total, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Trust Badges -->
                <div class="row g-3 mt-2">
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:var(--white);border:1px solid var(--border);">
                            <i class="bi bi-shield-check-fill text-success fs-4"></i>
                            <div>
                                <div style="font-size:.78rem;font-weight:700;">Secure Checkout</div>
                                <div style="font-size:.7rem;color:var(--muted);">256-bit SSL</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:var(--white);border:1px solid var(--border);">
                            <i class="bi bi-truck fs-4" style="color:var(--brand-dk);"></i>
                            <div>
                                <div style="font-size:.78rem;font-weight:700;">Free Delivery</div>
                                <div style="font-size:.7rem;color:var(--muted);">Orders over £50</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:var(--white);border:1px solid var(--border);">
                            <i class="bi bi-arrow-counterclockwise fs-4 text-primary"></i>
                            <div>
                                <div style="font-size:.78rem;font-weight:700;">Easy Returns</div>
                                <div style="font-size:.7rem;color:var(--muted);">Hassle-free pickup</div>
                            </div>
                        </div>
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
                        @foreach($cartItems as $row)
                            <div class="summary-row">
                                <span style="color:var(--muted);font-size:.83rem;">{{ Str::limit($row['title'], 22) }} × {{ $row['qty'] }}</span>
                                <span style="font-weight:600;">£{{ number_format($row['price'] * $row['qty'], 2) }}</span>
                            </div>
                        @endforeach
                        <div class="summary-row">
                            <span style="color:var(--muted);">Delivery</span>
                            <span style="color:var(--success);font-weight:600;"><i class="bi bi-check-circle-fill me-1"></i>Free</span>
                        </div>
                        <div class="summary-total border-top pt-3 mt-1">
                            <span>Total / Day</span>
                            <span style="color:var(--dark);">£{{ number_format($total, 2) }}</span>
                        </div>
                        <p style="font-size:.75rem;color:var(--muted);margin-top:.4rem;">
                            * Final total calculated at checkout based on rental duration
                        </p>

                        <a href="/checkout" class="btn-checkout">
                            <i class="bi bi-lock-fill"></i> Proceed to Checkout
                        </a>
                        <a href="/" class="btn-continue">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Empty Cart -->
        <div class="cart-wrap">
            <div class="empty-cart">
                <div class="empty-icon"><i class="bi bi-cart-x"></i></div>
                <h4 style="font-weight:800;">Your cart is empty</h4>
                <p style="color:var(--muted);max-width:340px;margin:0 auto 1.5rem;">
                    Looks like you haven't added any equipment yet. Browse our catalogue and find what you need.
                </p>
                <a href="/#items" class="btn-brand text-decoration-none d-inline-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Browse Equipment
                </a>
            </div>
        </div>
    @endif

</div>

@endsection
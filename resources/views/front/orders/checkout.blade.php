@extends('front.layouts.app')
@section('title', 'Checkout — '.$product->title)
@section('content')
<style>
.checkout-section { padding: 3rem 0 5rem; background: #F5F4EF; min-height: 80vh; }
.checkout-breadcrumb { background: #f5f4ef; padding: 1rem 0; border-bottom: 1px solid #e8e6de; font-size: .85rem; color: #888; }
.checkout-breadcrumb a { color: #888; text-decoration: none; }
.checkout-breadcrumb a:hover { color: var(--brand); }
.checkout-breadcrumb span { margin: 0 .5rem; }
.checkout-grid { display: grid; grid-template-columns: 1fr 400px; gap: 2.5rem; align-items: start; }
@media(max-width: 960px) { .checkout-grid { grid-template-columns: 1fr; } }
.checkout-card { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 2rem; }
.checkout-title { font-size: 1.5rem; font-weight: 800; color: #111; margin-bottom: 1.5rem; }
.form-label { font-size: .8rem; font-weight: 700; text-transform: uppercase; color: #555; }
.form-control { border-radius: 10px; border: 1px solid #ddd; padding: .75rem 1rem; }
.form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 .25rem rgba(255,199,0,.25); }
.order-summary { background: #fff; border-radius: 16px; border: 1px solid #e5e4df; padding: 2rem; position: sticky; top: 80px; box-shadow: var(--shadow); }
.summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: .95rem; }
.summary-total { display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 800; border-top: 1px solid #eee; padding-top: 1rem; margin-top: 1rem; }
.btn-place { width: 100%; padding: 1rem; border: none; border-radius: 12px; background: var(--brand); color: #111; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: all .2s; }
.btn-place:hover { background: var(--brand-dk); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,199,0,.3); }
.product-preview { display: flex; gap: 1rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #eee; }
.product-preview img { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; }
.product-info { flex: 1; }
.product-title { font-weight: 800; font-size: 1.1rem; margin-bottom: .25rem; }
.product-vendor { font-size: .85rem; color: #888; }
.product-price { font-weight: 800; color: #16a34a; font-size: 1.2rem; }
</style>

<div class="checkout-breadcrumb">
    <div class="container">
        <a href="/">Home</a><span>/</span>
        <a href="/marketplace">Marketplace</a><span>/</span>
        Checkout
    </div>
</div>

<div class="checkout-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('front.place-order') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="checkout-grid">
                <!-- Left: Shipping info -->
                <div class="checkout-card">
                    <h2 class="checkout-title">Shipping & Order Details</h2>
                    
                    <div class="mb-4">
                        <label class="form-label">Shipping Address *</label>
                        <textarea name="shipping_address" class="form-control" rows="4" required placeholder="Enter full shipping address...">{{ old('shipping_address') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Order Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div>
                    <div class="order-summary">
                        <h3 style="font-weight: 800; font-size: 1.2rem; margin-bottom: 1.5rem;">Order Summary</h3>
                        
                        <div class="product-preview">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/'.$product->images[0]) }}" alt="{{ $product->title }}">
                            @else
                                <div style="width:100px;height:100px;background:#f5f5f5;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <div class="product-info">
                                <div class="product-title">{{ $product->title }}</div>
                                <div class="product-vendor">Vendor: {{ $product->vendor->name ?? 'Unknown' }}</div>
                                <span class="badge bg-light text-dark mt-1 border">{{ ucfirst($product->condition ?? 'New') }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="qtyInput" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock ?? 1 }}" required onchange="updateTotal()">
                        </div>

                        <div class="summary-item">
                            <span style="color:#666;">Unit Price</span>
                            <span style="font-weight:700;">£<span id="unitPrice">{{ number_format($product->price, 2, '.', '') }}</span></span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span style="color:var(--brand-dk);">£<span id="totalPrice">{{ number_format($product->price, 2, '.', '') }}</span></span>
                        </div>

                        <button type="submit" class="btn-place mt-4">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateTotal() {
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const price = parseFloat(document.getElementById('unitPrice').innerText);
    const total = qty * price;
    document.getElementById('totalPrice').innerText = total.toFixed(2);
}
</script>
@endsection

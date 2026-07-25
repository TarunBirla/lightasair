@extends('front.layouts.app')

@section('title', $product->title . ' — Light As Air Marketplace')
@section('meta_description', Str::limit(strip_tags($product->description), 155))

@section('content')

<style>
/* ── PRODUCT DETAIL PAGE ─────────────────────────────────── */
.pd-breadcrumb {
    background: #f5f4ef;
    padding: .85rem 0;
    border-bottom: 1px solid #e8e6de;
    font-size: .8rem; color: #888;
}
.pd-breadcrumb a { color: #888; text-decoration: none; }
.pd-breadcrumb a:hover { color: #FFC700; }
.pd-breadcrumb span { margin: 0 .4rem; }

.pd-section { background: #F5F4EF; padding: 2.5rem 0 4rem; }

/* ── MAIN GRID ───────────────────────────────────────────── */
.pd-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    align-items: start;
}
@media(max-width: 960px) { .pd-grid { grid-template-columns: 1fr; } }

/* ── IMAGE GALLERY ───────────────────────────────────────── */
.pd-gallery {
    background: #fff; border-radius: 16px;
    border: 1px solid #e5e4df;
    overflow: hidden;
}
.pd-main-img {
    width: 100%; height: 440px;
    object-fit: contain; background: #f8f8f6;
    display: block;
}
.pd-thumbs {
    display: flex; gap: .6rem; padding: 1rem;
    overflow-x: auto; background: #fafaf8;
    border-top: 1px solid #f0ede8;
}
.pd-thumb {
    width: 80px; height: 68px; flex-shrink: 0;
    object-fit: cover; border-radius: 8px;
    border: 2px solid transparent; cursor: pointer;
    transition: border-color .15s;
}
.pd-thumb.active, .pd-thumb:hover { border-color: #FFC700; }

/* ── DETAILS PANEL ───────────────────────────────────────── */
.pd-info-card {
    background: #fff; border-radius: 16px;
    border: 1px solid #e5e4df; padding: 1.6rem;
    position: sticky; top: 80px;
}
.pd-badges { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: .9rem; }
.pd-badge {
    padding: .25rem .75rem; border-radius: 20px;
    font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em;
}
.pd-badge-new       { background: #dcfce7; color: #166534; }
.pd-badge-used      { background: #fef3c7; color: #92400e; }
.pd-badge-refurbished { background: #dbeafe; color: #1e40af; }
.pd-badge-cat       { background: #f3f4f6; color: #374151; }
.pd-badge-featured  { background: #FFC700; color: #111; }

.pd-title {
    font-size: 1.5rem; font-weight: 900; color: #111;
    line-height: 1.25; margin-bottom: .3rem;
}
.pd-subtitle { font-size: .9rem; color: #888; margin-bottom: 1.1rem; }

.pd-price {
    font-size: 2.2rem; font-weight: 900; color: #111;
    margin-bottom: 1.2rem; line-height: 1;
}
.pd-price .curr { font-size: 1.3rem; font-weight: 700; vertical-align: super; margin-right: 1px; }

/* CTA Buttons */
.pd-cta-group { display: flex; flex-direction: column; gap: .65rem; margin-bottom: 1.4rem; }
.btn-enquire {
    width: 100%; padding: 1rem; border: none; border-radius: 12px;
    background: linear-gradient(135deg, #FFC700, #FFB300);
    color: #111; font-weight: 900; font-size: 1rem;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 16px rgba(255,199,0,.3);
    text-decoration: none; display: block; text-align: center;
}
.btn-enquire:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,199,0,.4); color: #111; }
.btn-save {
    width: 100%; padding: .8rem; border: 2px solid #e5e4df;
    border-radius: 12px; background: transparent;
    color: #555; font-weight: 700; font-size: .9rem;
    cursor: pointer; transition: all .2s;
    text-decoration: none; display: block; text-align: center;
}
.btn-save:hover { border-color: #FFC700; color: #111; }

/* Specs Table */
.pd-specs { margin-bottom: 1.2rem; }
.pd-specs-title {
    font-size: .75rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .06em; color: #888; margin-bottom: .75rem;
    padding-bottom: .5rem; border-bottom: 1px solid #f0ede8;
}
.spec-row {
    display: flex; justify-content: space-between;
    padding: .45rem 0; border-bottom: 1px solid #f8f6f0;
    font-size: .85rem;
}
.spec-row:last-child { border: none; }
.spec-label { color: #888; font-weight: 600; }
.spec-value { color: #111; font-weight: 700; text-align: right; }

/* Seller Card */
.seller-card {
    background: #F5F4EF; border-radius: 12px;
    padding: 1rem; display: flex; align-items: center; gap: .85rem;
    text-decoration: none;
}
.seller-avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: #FFC700; display: flex; align-items: center; justify-content:center;
    font-weight: 900; font-size: 1.1rem; color: #111; flex-shrink: 0;
    overflow: hidden;
}
.seller-avatar img { width: 100%; height: 100%; object-fit: cover; }
.seller-name { font-weight: 800; font-size: .9rem; color: #111; }
.seller-meta { font-size: .75rem; color: #888; }
.seller-badge {
    margin-left: auto; background: #dcfce7; color: #166534;
    font-size: .65rem; font-weight: 800; padding: .2rem .6rem;
    border-radius: 20px; flex-shrink: 0;
}

/* ── DESCRIPTION SECTION ─────────────────────────────────── */
.pd-desc-section {
    background: #fff; border-radius: 16px;
    border: 1px solid #e5e4df; padding: 1.8rem;
    margin-top: 1.5rem;
}
.pd-desc-section h2 {
    font-size: 1.1rem; font-weight: 800; color: #111;
    margin-bottom: 1rem; padding-bottom: .6rem;
    border-bottom: 2px solid #FFC700; display: inline-block;
}
.pd-desc-text { font-size: .92rem; line-height: 1.8; color: #444; white-space: pre-line; }

/* ── RELATED ──────────────────────────────────────────────── */
.related-section { margin-top: 3rem; }
.section-heading {
    font-size: 1.4rem; font-weight: 900; color: #111;
    margin-bottom: 1.2rem;
}
.section-heading span { color: #FFC700; }
.related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.related-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #e5e4df; overflow: hidden;
    text-decoration: none; color: inherit;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column;
}
.related-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
.related-img { height: 140px; overflow: hidden; background: #f5f5f5; }
.related-img img { width: 100%; height: 100%; object-fit: cover; }
.related-body { padding: .85rem; flex: 1; display: flex; flex-direction: column; }
.related-name { font-size: .88rem; font-weight: 700; color: #111; margin-bottom: .3rem; flex: 1; }
.related-price { font-size: 1.05rem; font-weight: 900; color: #111; }
</style>

{{-- BREADCRUMB --}}
<div class="pd-breadcrumb">
    <div class="container">
        <a href="/">Home</a><span>/</span>
        <a href="{{ url('/marketplace') }}">Marketplace</a><span>/</span>
        @if($product->category)
        <a href="{{ url('/marketplace?category=' . $product->category_id) }}">{{ $product->category->name }}</a><span>/</span>
        @endif
        {{ Str::limit($product->title, 45) }}
    </div>
</div>

{{-- MAIN --}}
<div class="pd-section">
    <div class="container">
        <div class="pd-grid">

            {{-- LEFT: Gallery + Description --}}
            <div>
                {{-- Gallery --}}
                <div class="pd-gallery">
                    @if($product->images && count($product->images))
                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                             id="pdMainImg" class="pd-main-img" alt="{{ $product->title }}">
                        @if(count($product->images) > 1)
                        <div class="pd-thumbs">
                            @foreach($product->images as $i => $img)
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                                 onclick="switchImg(this, '{{ asset('storage/' . $img) }}')"
                                 alt="Image {{ $i+1 }}">
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div style="height:440px;display:flex;align-items:center;justify-content:center;background:#f8f8f6;color:#ccc;flex-direction:column;gap:.5rem;">
                            <i class="bi bi-image" style="font-size:3rem;"></i>
                            <span>No images available</span>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="pd-desc-section">
                    <h2>Description</h2>
                    <div class="pd-desc-text">{{ $product->description }}</div>
                </div>
            </div>

            {{-- RIGHT: Info Panel --}}
            <div>
                <div class="pd-info-card">

                    <div class="pd-badges">
                        <span class="pd-badge pd-badge-{{ $product->condition }}">{{ ucfirst($product->condition) }}</span>
                        @if($product->category)
                            <span class="pd-badge pd-badge-cat">{{ $product->category->name }}</span>
                        @endif
                        @if($product->is_featured)
                            <span class="pd-badge pd-badge-featured"><i class="bi bi-star-fill me-1"></i>Featured</span>
                        @endif
                    </div>

                    <h1 class="pd-title">{{ $product->title }}</h1>
                    @if($product->short_description)
                    <p class="pd-subtitle">{{ $product->short_description }}</p>
                    @endif

                    <div class="pd-price">
                        <span class="curr">£</span>{{ number_format($product->price, 2) }}
                    </div>

                    <div class="pd-cta-group">
                        @auth
                            <a href="mailto:{{ $product->seller->email }}?subject=Enquiry: {{ $product->title }}" class="btn-enquire">
                                <i class="bi bi-envelope-fill me-2"></i> Contact Seller
                            </a>
                        @else
                            <a href="/login" class="btn-enquire">
                                <i class="bi bi-lock-fill me-2"></i> Login to Enquire
                            </a>
                        @endauth
                        <a href="#" class="btn-save">
                            <i class="bi bi-heart me-2"></i> Save Listing
                        </a>
                    </div>

                    {{-- Specs --}}
                    <div class="pd-specs">
                        <div class="pd-specs-title">Product Details</div>
                        @if($product->brand)
                        <div class="spec-row">
                            <span class="spec-label">Brand</span>
                            <span class="spec-value">{{ $product->brand }}</span>
                        </div>
                        @endif
                        @if($product->model_number)
                        <div class="spec-row">
                            <span class="spec-label">Model</span>
                            <span class="spec-value">{{ $product->model_number }}</span>
                        </div>
                        @endif
                        @if($product->year_manufactured)
                        <div class="spec-row">
                            <span class="spec-label">Year</span>
                            <span class="spec-value">{{ $product->year_manufactured }}</span>
                        </div>
                        @endif
                        <div class="spec-row">
                            <span class="spec-label">Quantity Available</span>
                            <span class="spec-value">{{ $product->quantity }}</span>
                        </div>
                        @if($product->location)
                        <div class="spec-row">
                            <span class="spec-label"><i class="bi bi-geo-alt-fill"></i> Location</span>
                            <span class="spec-value">{{ $product->location }}</span>
                        </div>
                        @endif
                        <div class="spec-row">
                            <span class="spec-label">Collection</span>
                            <span class="spec-value">{{ $product->offers_collection ? '✓ Available' : '✗ Not offered' }}</span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label">Shipping</span>
                            <span class="spec-value">{{ $product->offers_shipping ? '✓ Available' : '✗ Not offered' }}</span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label">Listed</span>
                            <span class="spec-value">{{ $product->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label">Views</span>
                            <span class="spec-value">{{ $product->view_count }}</span>
                        </div>
                    </div>

                    {{-- Seller --}}
                    <div class="pd-specs-title">Seller</div>
                    <div class="seller-card">
                        <div class="seller-avatar">
                            @if($product->seller->vendorProfile?->logo)
                                <img src="{{ asset('storage/' . $product->seller->vendorProfile->logo) }}" alt="Logo">
                            @else
                                {{ strtoupper(substr($product->seller->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="seller-name">{{ $product->seller->company_name ?? $product->seller->name }}</div>
                            <div class="seller-meta">Member since {{ $product->seller->created_at->format('M Y') }}</div>
                        </div>
                        <span class="seller-badge"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                    </div>

                </div>
            </div>

        </div>

        {{-- Related Listings --}}
        @if($related->isNotEmpty())
        <div class="related-section">
            <h2 class="section-heading">Similar <span>Equipment</span></h2>
            <div class="related-grid">
                @foreach($related as $rel)
                <a href="{{ url('/marketplace/' . $rel->slug) }}" class="related-card">
                    <div class="related-img">
                        <img src="{{ $rel->primaryImageUrl() }}" alt="{{ $rel->title }}" loading="lazy">
                    </div>
                    <div class="related-body">
                        <div class="related-name">{{ Str::limit($rel->title, 55) }}</div>
                        <div class="related-price">£{{ number_format($rel->price, 2) }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script>
function switchImg(thumb, src) {
    document.getElementById('pdMainImg').src = src;
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>

@endsection

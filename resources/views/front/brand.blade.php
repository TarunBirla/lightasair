{{-- resources/views/front/brand.blade.php --}}

<style>
/* ── BRAND SECTION ── */
.brand-section {
    padding: 80px 0;
    background: #0a0a0a;
    position: relative;
    overflow: hidden;
}

.brand-section::before {
    content: '';
    position: absolute;
    top: -80px;
    left: 50%;
    transform: translateX(-50%);
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(255,199,0,0.07) 0%, transparent 70%);
    pointer-events: none;
}

.brand-section-label {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(255,199,0,0.1);
    border: 1px solid rgba(255,199,0,0.3);
    color: #FFC700;
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    padding: .4rem 1rem;
    border-radius: 30px;
    margin-bottom: 1rem;
}

.brand-section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
    margin-bottom: .6rem;
}

.brand-section-title span {
    color: #FFC700;
}

.brand-section-sub {
    color: rgba(255,255,255,0.45);
    font-size: .95rem;
    font-weight: 400;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Divider */
.brand-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin: 2rem 0 3rem;
}

.brand-divider::before,
.brand-divider::after {
    content: '';
    flex: 1;
    max-width: 120px;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255,199,0,0.4));
}

.brand-divider::after {
    background: linear-gradient(to left, transparent, rgba(255,199,0,0.4));
}

.brand-divider-dot {
    width: 8px;
    height: 8px;
    background: #FFC700;
    border-radius: 50%;
    box-shadow: 0 0 12px #FFC700;
}

/* Brand Grid */
.brand-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.5rem;
}

/* Brand Card */
.brand-card {
    position: relative;
    background: #FFC700;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 16px;
    /* padding: 2rem 1.5rem; */
    text-align: center;
    overflow: hidden;
    transition: transform .35s cubic-bezier(.23,1,.32,1),
                border-color .35s,
                box-shadow .35s;
    cursor: pointer;
}

.brand-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,199,0,0.05), transparent 60%);
    opacity: 0;
    transition: opacity .35s;
    border-radius: inherit;
}

.brand-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #FFC700, transparent);
    transform: scaleX(0);
    transition: transform .35s;
}

.brand-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255,199,0,0.35);
    box-shadow: 0 20px 50px rgba(0,0,0,0.5),
                0 0 30px rgba(255,199,0,0.08);
}

.brand-card:hover::before {
    opacity: 1;
}

.brand-card:hover::after {
    transform: scaleX(1);
}

.brand-img-wrap {
   
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    /* margin: 0 auto 1.2rem; */
    transition: background .35s, border-color .35s, transform .35s;
    overflow: hidden;
}

.brand-card:hover .brand-img-wrap {
    background: rgba(255,199,0,0.08);
    border-color: rgba(255,199,0,0.25);
    transform: scale(1.06);
}

/* .brand-img-wrap img {
    width: 150px;
    height: 120px;
    object-fit: contain;
    filter: grayscale(30%) brightness(1.1);
    transition: filter .35s;
} */

.brand-img-wrap {
    width: 100%;
    height: 180px;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* image cut nahi hogi */
    object-position: center;
}
.brand-card:hover .brand-img-wrap img {
    filter: grayscale(0%) brightness(1.2);
}

.brand-card h5 {
    font-size: .92rem;
    font-weight: 700;
    color: rgba(255,255,255,0.75);
    margin: 0;
    transition: color .35s;
    letter-spacing: .02em;
}

.brand-card:hover h5 {
    color: #FFC700;
}

.brand-card-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 0deg at 50% 50%, transparent 340deg, rgba(255,199,0,0.05) 355deg, transparent 360deg);
    opacity: 0;
    transition: opacity .5s;
    pointer-events: none;
}

.brand-card:hover .brand-card-shine {
    opacity: 1;
    animation: shine-rotate 3s linear infinite;
}

@keyframes shine-rotate {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 991px) {
    .brand-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 767px) {
    .brand-section {
        padding: 60px 0;
    }
    .brand-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .brand-img-wrap {
        height: 120px;
        padding: 10px;
    }

    .brand-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
}

@media (max-width: 400px) {
    .brand-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<section class="brand-section">
    <div class="container">

        <!-- Heading -->
        <div class="text-center">
            <div class="brand-section-label">
                <i class="bi bi-patch-check-fill"></i> Trusted Partners
            </div>
            <h2 class="brand-section-title">
                Production Houses <span>We Trust</span>
            </h2>
            <p class="brand-section-sub">
                We partner with the world's leading equipment manufacturers to bring you only the finest rental gear.
            </p>
        </div>

        <div class="brand-divider">
            <div class="brand-divider-dot"></div>
        </div>

        <!-- Brand Grid -->
        <div class="brand-grid">
            @foreach($brands as $brand)
                <div class="brand-card">
                    <div class="brand-card-shine"></div>
                    <div class="brand-img-wrap">
                        <img src="{{ asset('uploads/brands/' . $brand->image) }}" alt="{{ $brand->title }}">
                    </div>
                    <!-- <h5>{{ $brand->title }}</h5> -->
                </div>
            @endforeach
        </div>

    </div>
</section>
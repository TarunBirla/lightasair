{{-- resources/views/front/television.blade.php --}}

<style>
/* ── TELEVISION SECTION ── */
.tv-section {
    padding: 80px 0;
    background: #fff;
    position: relative;
    overflow: hidden;
}

.tv-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,199,0,0.3), transparent);
}

.tv-section-label {
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

.tv-section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #000;
    line-height: 1.1;
    margin-bottom: .6rem;
    /* text-transform: uppercase; */
}

.tv-section-title span {
    color: #FFC700;
}

.tv-section-sub {
    color: #000;
    font-size: .95rem;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.6;
}

.tv-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin: 2rem 0 3rem;
}

.tv-divider::before,
.tv-divider::after {
    content: '';
    flex: 1;
    max-width: 120px;
    height: 1px;
    background: linear-gradient(to right, transparent, #000);
}

.tv-divider::after {
    background: linear-gradient(to left, transparent, #000);
}

.tv-divider-dot {
    width: 8px;
    height: 8px;
    background: #000;
    border-radius: 50%;
    box-shadow: 0 0 12px #000;
}

/* ── SLIDER WRAPPER ── */
.tv-slider-outer {
    position: relative;
}

.tv-slider-track-wrap {
    overflow: hidden;
    border-radius: 18px;
}

.tv-slider-track {
    display: flex;
    transition: transform .55s cubic-bezier(.23,1,.32,1);
    will-change: transform;
}

/* TV Card */
.tv-card {
    flex: 0 0 calc(33.333% - 1rem);
    margin-right: 1.5rem;
    background: #111;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 18px;
    overflow: hidden;
    cursor: pointer;
    transition: border-color .35s, box-shadow .35s, transform .35s;
    position: relative;
}

.tv-card:hover {
    border-color: rgba(255,199,0,0.35);
    box-shadow: 0 20px 50px rgba(0,0,0,0.5),
                0 0 30px rgba(255,199,0,0.08);
    transform: translateY(-6px);
}

.tv-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 240px;
}

.tv-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s cubic-bezier(.23,1,.32,1);
    display: block;
}

.tv-card:hover .tv-card-img-wrap img {
    transform: scale(1.08);
}

.tv-card-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 55%);
    opacity: 0;
    transition: opacity .35s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tv-card:hover .tv-card-img-overlay {
    opacity: 1;
}

.tv-play-btn {
    width: 56px;
    height: 56px;
    background: #FFC700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-size: 1.3rem;
    transform: scale(0.7);
    transition: transform .3s;
}

.tv-card:hover .tv-play-btn {
    transform: scale(1);
}

.tv-card-body {
    padding: 1.4rem 1.5rem;
}

.tv-tag {
    display: inline-block;
    background: rgba(255,199,0,0.12);
    color: #FFC700;
    border: 1px solid rgba(255,199,0,0.25);
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .25rem .7rem;
    border-radius: 20px;
    margin-bottom: .8rem;
}

.tv-card-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: .6rem;
    line-height: 1.3;
    transition: color .25s;
}

.tv-card:hover .tv-card-title {
    color: #FFC700;
}

.tv-card-desc {
    font-size: .85rem;
    color: rgba(255,255,255,0.45);
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Slider Controls */
.tv-slider-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 2.5rem;
}

.tv-slider-btn {
    width: 46px;
    height: 46px;
    background: #FFC700;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    transition: background .2s, border-color .2s, color .2s;
}

.tv-slider-btn:hover {
    background: #FFC700;
    border-color: #FFC700;
    color: #000;
}

.tv-slider-dots {
    display: flex;
    gap: .5rem;
    align-items: center;
}

.tv-dot {
    width: 8px;
    height: 8px;
    border-radius: 4px;
    background: #FFC700;
    cursor: pointer;
    transition: background .3s, width .3s;
}

.tv-dot.active {
    background: #FFC700;
    width: 22px;
}

/* ── TV LIGHTBOX ── */
.tv-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.97);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    animation: tv-lb-in .25s ease;
}

.tv-lightbox.active {
    display: flex;
}

@keyframes tv-lb-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.tv-lb-inner {
    position: relative;
    width: 100%;
    max-width: 860px;
    padding: 0 70px;
}

.tv-lb-slide {
    display: none;
}

.tv-lb-slide.active {
    display: block;
    animation: tv-lb-slide-in .4s cubic-bezier(.23,1,.32,1);
}

@keyframes tv-lb-slide-in {
    from { transform: scale(0.92); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}

.tv-lb-img-wrap {
    width: 100%;
    max-height: 55vh;
    overflow: hidden;
    border-radius: 14px;
    margin-bottom: 1.5rem;
}

.tv-lb-img-wrap img {
    width: 100%;
    height: 100%;
    max-height: 55vh;
    object-fit: cover;
    display: block;
}

.tv-lb-info {
    text-align: center;
}

.tv-lb-tag {
    display: inline-block;
    background: rgba(255,199,0,0.12);
    color: #FFC700;
    border: 1px solid rgba(255,199,0,0.25);
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .25rem .7rem;
    border-radius: 20px;
    margin-bottom: .7rem;
}

.tv-lb-title {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: .7rem;
}

.tv-lb-desc {
    color: rgba(255,255,255,0.5);
    font-size: .9rem;
    line-height: 1.6;
    max-width: 560px;
    margin: 0 auto;
}

.tv-lb-close {
    position: fixed;
    top: 1.5rem;
    right: 1.5rem;
    width: 46px;
    height: 46px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background .2s;
    z-index: 10;
}

.tv-lb-close:hover {
    background: #FFC700;
    color: #000;
}

.tv-lb-arrow {
    position: absolute;
    top: 30%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background .2s, color .2s;
    z-index: 10;
}

.tv-lb-arrow:hover {
    background: #FFC700;
    color: #000;
}

.tv-lb-prev-btn { left: 0; }
.tv-lb-next-btn { right: 0; }

.tv-lb-counter {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    color: rgba(255,255,255,0.5);
    font-size: .82rem;
    font-weight: 600;
    letter-spacing: .1em;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    padding: .35rem 1rem;
    border-radius: 20px;
}

.tv-lb-counter span {
    color: #FFC700;
}

/* Responsive */
@media (max-width: 991px) {
    .tv-card {
        flex: 0 0 calc(50% - .75rem);
    }
}

@media (max-width: 600px) {
    .tv-section {
        padding: 60px 0;
    }
    .tv-card {
        flex: 0 0 calc(85vw);
        margin-right: 1rem;
    }
    .tv-lb-inner {
        padding: 0 48px;
    }
    .tv-lb-arrow {
        width: 38px;
        height: 38px;
        font-size: .9rem;
    }
}
</style>

<section class="tv-section">
    <div class="container">

        <!-- Heading -->
        <div class="text-center">
            <div class="tv-section-label">
                <i class="bi bi-display"></i> Portfolio — Our Work
            </div>
            <h2 class="tv-section-title">
                Every Frame Deserves  <span>Light</span>
            </h2>
            <p class="tv-section-sub">
                A selection of productions we have supported with professional lighting across London and the UK
            </p>
        </div>

        <div class="tv-divider">
            <div class="tv-divider-dot"></div>
        </div>

        <!-- Slider -->
        <div class="tv-slider-outer">
            <div class="tv-slider-track-wrap">
                <div class="tv-slider-track" id="tvSliderTrack">
                    @foreach($televisions as $index => $tv)
                        <div class="tv-card" onclick="openTvLightbox({{ $index }})">
                            <div class="tv-card-img-wrap">
                                <img src="{{ asset('uploads/televisions/' . $tv->image) }}" alt="{{ $tv->title }}" loading="lazy">
                                <div class="tv-card-img-overlay">
                                    <div class="tv-play-btn">
                                        <i class="bi bi-arrows-fullscreen"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tv-card-body">
                                @if($tv->tag)
                                    <div class="tv-tag">{{ $tv->tag }}</div>
                                @endif
                                <div class="tv-card-title">{{ $tv->title }}</div>
                                <p class="tv-card-desc">{{ $tv->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Controls -->
            <div class="tv-slider-controls">
                <button class="tv-slider-btn" id="tvPrevBtn">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <div class="tv-slider-dots" id="tvDots"></div>
                <button class="tv-slider-btn" id="tvNextBtn">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

    </div>
</section>

<!-- TV Lightbox -->
<div class="tv-lightbox" id="tvLightbox">
    <button class="tv-lb-close" onclick="closeTvLightbox()">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="tv-lb-inner">
        <button class="tv-lb-arrow tv-lb-prev-btn" onclick="tvLbPrev()">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div id="tvLbSlides">
            @foreach($televisions as $index => $tv)
                <div class="tv-lb-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <div class="tv-lb-img-wrap">
                        <img src="{{ asset('uploads/televisions/' . $tv->image) }}" alt="{{ $tv->title }}">
                    </div>
                    <div class="tv-lb-info">
                        @if($tv->tag)
                            <div class="tv-lb-tag">{{ $tv->tag }}</div>
                        @endif
                        <div class="tv-lb-title">{{ $tv->title }}</div>
                        <p class="tv-lb-desc">{{ $tv->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="tv-lb-arrow tv-lb-next-btn" onclick="tvLbNext()">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <div class="tv-lb-counter">
        <span id="tvLbCurrent">1</span> / <span id="tvLbTotal">1</span>
    </div>
</div>

<script>
(function () {
    /* ── MAIN SLIDER ── */
    const track = document.getElementById('tvSliderTrack');
    const cards = track.querySelectorAll('.tv-card');
    const dotsContainer = document.getElementById('tvDots');
    const total = cards.length;
    let current = 0;
    let autoTimer;

    function getVisible() {
        if (window.innerWidth <= 600)  return 1;
        if (window.innerWidth <= 991)  return 2;
        return 3;
    }

    function buildDots() {
        dotsContainer.innerHTML = '';
        const pages = Math.ceil(total / getVisible());
        for (let i = 0; i < pages; i++) {
            const d = document.createElement('div');
            d.className = 'tv-dot' + (i === 0 ? ' active' : '');
            d.addEventListener('click', () => goTo(i * getVisible()));
            dotsContainer.appendChild(d);
        }
    }

    function updateDots() {
        const vis = getVisible();
        const page = Math.floor(current / vis);
        dotsContainer.querySelectorAll('.tv-dot').forEach((d, i) => {
            d.classList.toggle('active', i === page);
        });
    }

    function goTo(index) {
        const vis = getVisible();
        const maxIndex = Math.max(0, total - vis);
        current = Math.min(Math.max(index, 0), maxIndex);
        const cardWidth = cards[0].offsetWidth + 24; // gap 1.5rem = 24px
        track.style.transform = `translateX(-${current * cardWidth}px)`;
        updateDots();
    }

    function next() {
        const vis = getVisible();
        goTo(current + vis >= total ? 0 : current + vis);
    }

    function prev() {
        const vis = getVisible();
        goTo(current - vis < 0 ? Math.max(0, total - vis) : current - vis);
    }

    function startAuto() {
        stopAuto();
        autoTimer = setInterval(next, 3500);
    }

    function stopAuto() {
        clearInterval(autoTimer);
    }

    document.getElementById('tvNextBtn').addEventListener('click', () => { stopAuto(); next(); startAuto(); });
    document.getElementById('tvPrevBtn').addEventListener('click', () => { stopAuto(); prev(); startAuto(); });

    buildDots();
    startAuto();

    window.addEventListener('resize', () => {
        buildDots();
        goTo(0);
    });

    /* ── TV LIGHTBOX ── */
    const slides = document.querySelectorAll('.tv-lb-slide');
    const lbTotal = slides.length;
    let lbIndex = 0;

    document.getElementById('tvLbTotal').textContent = lbTotal;

    window.openTvLightbox = function (index) {
        lbIndex = index;
        updateTvLb();
        document.getElementById('tvLightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeTvLightbox = function () {
        document.getElementById('tvLightbox').classList.remove('active');
        document.body.style.overflow = '';
    };

    window.tvLbNext = function () {
        lbIndex = (lbIndex + 1) % lbTotal;
        updateTvLb();
    };

    window.tvLbPrev = function () {
        lbIndex = (lbIndex - 1 + lbTotal) % lbTotal;
        updateTvLb();
    };

    function updateTvLb() {
        slides.forEach((s, i) => s.classList.toggle('active', i === lbIndex));
        document.getElementById('tvLbCurrent').textContent = lbIndex + 1;
    }

    document.addEventListener('keydown', function (e) {
        const lb = document.getElementById('tvLightbox');
        if (!lb.classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  tvLbPrev();
        if (e.key === 'ArrowRight') tvLbNext();
        if (e.key === 'Escape')     closeTvLightbox();
    });

    document.getElementById('tvLightbox').addEventListener('click', function (e) {
        if (e.target === this) closeTvLightbox();
    });
})();
</script>
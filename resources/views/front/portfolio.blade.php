{{-- resources/views/front/portfolio.blade.php --}}

<style>
/* ── PORTFOLIO SECTION ── */
.portfolio-section {
    padding: 80px 0;
    background: #0d0d0d;
    position: relative;
    overflow: hidden;
}

.portfolio-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,199,0,0.3), transparent);
}

.portfolio-section-label {
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

.portfolio-section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
    margin-bottom: .6rem;
}

.portfolio-section-title span {
    color: #FFC700;
}

.portfolio-section-sub {
    color: rgba(255,255,255,0.45);
    font-size: .95rem;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.6;
}

.portfolio-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin: 2rem 0 3rem;
}

.portfolio-divider::before,
.portfolio-divider::after {
    content: '';
    flex: 1;
    max-width: 120px;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255,199,0,0.4));
}

.portfolio-divider::after {
    background: linear-gradient(to left, transparent, rgba(255,199,0,0.4));
}

.portfolio-divider-dot {
    width: 8px;
    height: 8px;
    background: #FFC700;
    border-radius: 50%;
    box-shadow: 0 0 12px #FFC700;
}

/* Grid */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

/* Portfolio Card */
.portfolio-item {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 1 / 1;
    background: #1a1a1a;
}

.portfolio-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s cubic-bezier(.23,1,.32,1);
    display: block;
}

.portfolio-item:hover img {
    transform: scale(1.1);
}

.portfolio-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 50%);
    opacity: 0;
    transition: opacity .35s;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 1.2rem;
}

.portfolio-item:hover .portfolio-overlay {
    opacity: 1;
}

.portfolio-zoom-btn {
    width: 42px;
    height: 42px;
    background: #FFC700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-size: 1rem;
    margin-left: auto;
    transition: transform .25s;
}

.portfolio-item:hover .portfolio-zoom-btn {
    transform: scale(1.1);
}

.portfolio-item-num {
    position: absolute;
    top: .75rem;
    left: .75rem;
    background: rgba(255,199,0,0.9);
    color: #000;
    font-size: .68rem;
    font-weight: 800;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0.7);
    transition: opacity .3s, transform .3s;
}

.portfolio-item:hover .portfolio-item-num {
    opacity: 1;
    transform: scale(1);
}

/* ── LIGHTBOX ── */
.portfolio-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.96);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    animation: lb-fade-in .25s ease;
}

.portfolio-lightbox.active {
    display: flex;
}

@keyframes lb-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.lb-inner {
    position: relative;
    width: 100%;
    max-width: 900px;
    padding: 0 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lb-img-wrap {
    width: 100%;
    max-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lb-img-wrap img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 40px 100px rgba(0,0,0,0.8);
    animation: lb-zoom-in .3s cubic-bezier(.23,1,.32,1);
}

@keyframes lb-zoom-in {
    from { transform: scale(0.88); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}

.lb-close {
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

.lb-close:hover {
    background: #FFC700;
    color: #000;
}

.lb-arrow {
    position: absolute;
    top: 50%;
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

.lb-arrow:hover {
    background: #FFC700;
    color: #000;
}

.lb-prev { left: 0; }
.lb-next { right: 0; }

.lb-counter {
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

.lb-counter span {
    color: #FFC700;
}

/* Responsive */
@media (max-width: 991px) {
    .portfolio-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 767px) {
    .portfolio-section {
        padding: 60px 0;
    }
    .portfolio-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: .75rem;
    }
    .lb-inner {
        padding: 0 50px;
    }
}

@media (max-width: 480px) {
    .portfolio-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .lb-inner {
        padding: 0 44px;
    }
    .lb-arrow {
        width: 38px;
        height: 38px;
        font-size: .9rem;
    }
}
</style>

<section class="portfolio-section">
    <div class="container">

        <!-- Heading -->
        <div class="text-center">
            <div class="portfolio-section-label">
                <i class="bi bi-images"></i> Our Work
            </div>
            <h2 class="portfolio-section-title">
                Lighting Rental Services for Film and <span>Television</span>
            </h2>
            <p class="portfolio-section-sub">
                A glimpse into the projects we've powered. Click any image to explore in full screen.
            </p>
        </div>

        <div class="portfolio-divider">
            <div class="portfolio-divider-dot"></div>
        </div>

        <!-- Portfolio Grid -->
        <div class="portfolio-grid" id="portfolioGrid">
            @foreach($portfolios as $index => $portfolio)
                <div class="portfolio-item" onclick="openLightbox({{ $index }})">
                    <img src="{{ asset('uploads/portfolios/' . $portfolio->image) }}" alt="Portfolio {{ $index + 1 }}" loading="lazy">
                    <div class="portfolio-overlay">
                        <div class="portfolio-zoom-btn">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </div>
                    </div>
                    <div class="portfolio-item-num">{{ $index + 1 }}</div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Lightbox -->
<div class="portfolio-lightbox" id="portfolioLightbox">
    <button class="lb-close" onclick="closeLightbox()">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="lb-inner">
        <button class="lb-arrow lb-prev" onclick="lbPrev()">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="lb-img-wrap">
            <img id="lbImage" src="" alt="Portfolio">
        </div>

        <button class="lb-arrow lb-next" onclick="lbNext()">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <div class="lb-counter">
        <span id="lbCurrent">1</span> / <span id="lbTotal">1</span>
    </div>
</div>

<script>
(function () {
    // Collect image sources from the grid
    const portfolioImages = Array.from(
        document.querySelectorAll('#portfolioGrid .portfolio-item img')
    ).map(img => img.src);

    let currentIndex = 0;

    window.openLightbox = function (index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById('portfolioLightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeLightbox = function () {
        document.getElementById('portfolioLightbox').classList.remove('active');
        document.body.style.overflow = '';
    };

    window.lbPrev = function () {
        currentIndex = (currentIndex - 1 + portfolioImages.length) % portfolioImages.length;
        updateLightbox();
    };

    window.lbNext = function () {
        currentIndex = (currentIndex + 1) % portfolioImages.length;
        updateLightbox();
    };

    function updateLightbox() {
        const img = document.getElementById('lbImage');
        img.style.animation = 'none';
        img.offsetHeight; // reflow
        img.style.animation = '';
        img.src = portfolioImages[currentIndex];
        document.getElementById('lbCurrent').textContent = currentIndex + 1;
        document.getElementById('lbTotal').textContent = portfolioImages.length;
    }

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        const lb = document.getElementById('portfolioLightbox');
        if (!lb.classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  lbPrev();
        if (e.key === 'ArrowRight') lbNext();
        if (e.key === 'Escape')     closeLightbox();
    });

    // Click backdrop to close
    document.getElementById('portfolioLightbox').addEventListener('click', function (e) {
        if (e.target === this) closeLightbox();
    });
})();
</script>
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
    bottom: 0; left: 0; right: 0;
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
.portfolio-section-title span { color: #FFC700; }
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
}
.portfolio-divider::before { background: linear-gradient(to right, transparent, rgba(255,199,0,0.4)); }
.portfolio-divider::after  { background: linear-gradient(to left,  transparent, rgba(255,199,0,0.4)); }
.portfolio-divider-dot {
    width: 8px; height: 8px;
    background: #FFC700;
    border-radius: 50%;
    box-shadow: 0 0 12px #FFC700;
}

/* ── DYNAMIC GRID ── */
.portfolio-rows { display: flex; flex-direction: column; gap: 8px; }

.pg-row {
    display: grid;
    gap: 8px;
}

/* Row A: big-left + 2 stacked right  |  6 + 3+3 */
.pg-row-a {
    grid-template-columns: 2fr 1fr;
    grid-template-rows: 200px 200px;
}
.pg-row-a .pg-big  { grid-column: 1; grid-row: 1 / 3; }
.pg-row-a .pg-sm   { grid-column: 2; }

/* Row B: 3 equal  |  4+4+4 */
.pg-row-b {
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: 200px;
}

/* Row C: 2 stacked left + big-right  |  3+3 + 6 */
.pg-row-c {
    grid-template-columns: 1fr 2fr;
    grid-template-rows: 200px 200px;
}
.pg-row-c .pg-big { grid-column: 2; grid-row: 1 / 3; }
.pg-row-c .pg-sm  { grid-column: 1; }

/* Portfolio item */
.pg-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    background: #1a1a1a;
}
.pg-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .5s cubic-bezier(.23,1,.32,1);
}
.pg-item:hover img { transform: scale(1.08); }

.pg-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 55%);
    opacity: 0;
    transition: opacity .3s;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    padding: 1rem;
}
.pg-item:hover .pg-overlay { opacity: 1; }

.pg-zoom-btn {
    width: 40px; height: 40px;
    background: #FFC700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-size: .95rem;
    transition: transform .2s;
}
.pg-item:hover .pg-zoom-btn { transform: scale(1.1); }

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
}
.portfolio-lightbox.active { display: flex; }

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
}
.lb-close {
    position: fixed;
    top: 1.5rem; right: 1.5rem;
    width: 46px; height: 46px;
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
.lb-close:hover { background: #FFC700; color: #000; }

.lb-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px; height: 50px;
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
}
.lb-arrow:hover { background: #FFC700; color: #000; }
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
    white-space: nowrap;
}
.lb-counter span { color: #FFC700; }

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    .pg-row-a,
    .pg-row-c {
        grid-template-rows: 160px 160px;
    }
    .pg-row-b {
        grid-template-rows: 160px;
    }
}

@media (max-width: 767px) {
    .portfolio-section { padding: 60px 0; }

    /* Tablet: all rows become 2-col equal */
    .pg-row-a,
    .pg-row-b,
    .pg-row-c {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
    }
    .pg-row-a .pg-big,
    .pg-row-c .pg-big {
        grid-column: auto;
        grid-row: auto;
        aspect-ratio: 16/9;
        height: auto;
    }
    .pg-row-a .pg-sm,
    .pg-row-c .pg-sm,
    .pg-row-b .pg-item {
        aspect-ratio: 1 / 1;
        height: auto;
    }
    .lb-inner { padding: 0 50px; }
}

@media (max-width: 480px) {
    /* Mobile: single column */
    .pg-row-a,
    .pg-row-b,
    .pg-row-c {
        grid-template-columns: 1fr;
    }
    .pg-row-a .pg-big,
    .pg-row-c .pg-big {
        aspect-ratio: 4/3;
    }
    .pg-row-a .pg-sm,
    .pg-row-c .pg-sm,
    .pg-row-b .pg-item {
        aspect-ratio: 4/3;
    }
    .lb-inner { padding: 0 44px; }
    .lb-arrow { width: 38px; height: 38px; font-size: .9rem; }
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

        <!-- ── PORTFOLIO GRID ── -->
        <div class="portfolio-rows" id="portfolioGrid">
            @php
                $images = $portfolios->values();
                $total  = $images->count();
                $idx    = 0;

                /*
                 * Pattern (9 images per cycle, then repeats):
                 *   Row A : 3 images  (1 big + 2 stacked)
                 *   Row B : 3 images  (3 equal)
                 *   Row C : 3 images  (2 stacked + 1 big)
                 */
                $rowTypes = ['a', 'b', 'c'];
                $rowSizes = ['a' => 3, 'b' => 3, 'c' => 3];
                $rowCycle = 0;
            @endphp

            @while($idx < $total)
                @php $type = $rowTypes[$rowCycle % 3]; @endphp

                @if($type === 'a')
                    {{-- Row A: big-left + 2 stacked-right --}}
                    <div class="pg-row pg-row-a">
                        @if(isset($images[$idx]))
                            <div class="pg-item pg-big" onclick="openLightbox({{ $idx }})">
                                <img src="{{ asset('uploads/portfolios/' . $images[$idx]->image) }}" alt="Portfolio {{ $idx+1 }}" loading="lazy">
                                <div class="pg-overlay"><div class="pg-zoom-btn"><i class="bi bi-arrows-fullscreen"></i></div></div>
                            </div>
                            @php $idx++; @endphp
                        @endif
                        @for($s = 0; $s < 2; $s++)
                            @if(isset($images[$idx]))
                                <div class="pg-item pg-sm" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ asset('uploads/portfolios/' . $images[$idx]->image) }}" alt="Portfolio {{ $idx+1 }}" loading="lazy">
                                    <div class="pg-overlay"><div class="pg-zoom-btn"><i class="bi bi-arrows-fullscreen"></i></div></div>
                                </div>
                                @php $idx++; @endphp
                            @endif
                        @endfor
                    </div>

                @elseif($type === 'b')
                    {{-- Row B: 3 equal --}}
                    <div class="pg-row pg-row-b">
                        @for($s = 0; $s < 3; $s++)
                            @if(isset($images[$idx]))
                                <div class="pg-item" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ asset('uploads/portfolios/' . $images[$idx]->image) }}" alt="Portfolio {{ $idx+1 }}" loading="lazy">
                                    <div class="pg-overlay"><div class="pg-zoom-btn"><i class="bi bi-arrows-fullscreen"></i></div></div>
                                </div>
                                @php $idx++; @endphp
                            @endif
                        @endfor
                    </div>

                @else
                    {{-- Row C: 2 stacked-left + big-right --}}
                    <div class="pg-row pg-row-c">
                        @for($s = 0; $s < 2; $s++)
                            @if(isset($images[$idx]))
                                <div class="pg-item pg-sm" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ asset('uploads/portfolios/' . $images[$idx]->image) }}" alt="Portfolio {{ $idx+1 }}" loading="lazy">
                                    <div class="pg-overlay"><div class="pg-zoom-btn"><i class="bi bi-arrows-fullscreen"></i></div></div>
                                </div>
                                @php $idx++; @endphp
                            @endif
                        @endfor
                        @if(isset($images[$idx]))
                            <div class="pg-item pg-big" onclick="openLightbox({{ $idx }})">
                                <img src="{{ asset('uploads/portfolios/' . $images[$idx]->image) }}" alt="Portfolio {{ $idx+1 }}" loading="lazy">
                                <div class="pg-overlay"><div class="pg-zoom-btn"><i class="bi bi-arrows-fullscreen"></i></div></div>
                            </div>
                            @php $idx++; @endphp
                        @endif
                    </div>
                @endif

                @php $rowCycle++; @endphp
            @endwhile
        </div>

    </div>
</section>

<!-- ── LIGHTBOX ── -->
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
    const portfolioImages = Array.from(
        document.querySelectorAll('#portfolioGrid .pg-item img')
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
        img.style.opacity = '0';
        img.src = portfolioImages[currentIndex];
        img.onload = () => { img.style.transition = 'opacity .25s'; img.style.opacity = '1'; };
        document.getElementById('lbCurrent').textContent = currentIndex + 1;
        document.getElementById('lbTotal').textContent = portfolioImages.length;
    }

    document.addEventListener('keydown', function (e) {
        const lb = document.getElementById('portfolioLightbox');
        if (!lb.classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  lbPrev();
        if (e.key === 'ArrowRight') lbNext();
        if (e.key === 'Escape')     closeLightbox();
    });

    document.getElementById('portfolioLightbox').addEventListener('click', function (e) {
        if (e.target === this) closeLightbox();
    });
})();
</script>
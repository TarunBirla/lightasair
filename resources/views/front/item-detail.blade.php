{{-- ── STYLES (replace the old gallery CSS) ── --}}
<style>
    .product-gallery {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .thumb-gallery {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 80px;
        flex-shrink: 0;
    }

    .thumb-image {
        width: 76px;
        height: 76px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: border-color .2s, transform .15s, box-shadow .15s;
        display: block;
    }

    .thumb-image:hover {
        border-color: #ffc700;
        transform: scale(1.05);
    }

    .thumb-image.active {
        border-color: #ffc700;
        box-shadow: 0 0 0 3px rgba(255, 199, 0, .25);
        transform: scale(1.05);
    }

    .main-gallery-image {
        flex: 1;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1.5px solid #e5e7eb;
        background: #fafafa;
        cursor: zoom-in;
        transition: border-color .2s;
    }

    .main-gallery-image:hover {
        border-color: #ffc700;
    }

    .main-gallery-image img {
        width: 100%;
        max-height: 420px;
        object-fit: contain;
        display: block;
        transition: opacity .22s, transform .22s;
    }

    .main-gallery-image img.fading {
        opacity: 0;
        transform: scale(0.97);
    }

    /* image counter badge */
    .img-count-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, .48);
        color: #fff;
        font-size: .72rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        pointer-events: none;
        letter-spacing: .03em;
    }

    /* zoom hint badge */
    .img-zoom-hint {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, .42);
        color: #fff;
        font-size: .72rem;
        padding: 4px 10px;
        border-radius: 6px;
        pointer-events: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* prev/next arrows on main image */
    .img-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, .35);
        border: none;
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .15s;
        z-index: 2;
    }

    .img-arrow:hover {
        background: rgba(0, 0, 0, .6);
    }

    .img-arrow.prev {
        left: 8px;
    }

    .img-arrow.next {
        right: 8px;
    }

    /* fullscreen lightbox */
    .image-modal .modal-content {
        background: rgba(0, 0, 0, .92);
        border: none;
        border-radius: 0;
        min-height: 100vh;
    }

    .image-modal .modal-body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }

    .image-modal .modal-body img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
    }

    .image-close {
        position: absolute;
        top: 16px;
        right: 20px;
        background: rgba(255, 255, 255, .15);
        border: none;
        color: #fff;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        font-size: 1.4rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .15s;
        z-index: 10;
    }

    .image-close:hover {
        background: rgba(255, 255, 255, .3);
    }

    /* lightbox nav */
    .lb-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, .15);
        border: none;
        color: #fff;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        font-size: 1.4rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .15s;
        z-index: 10;
    }

    .lb-arrow:hover {
        background: rgba(255, 255, 255, .3);
    }

    .lb-arrow.prev {
        left: 20px;
    }

    .lb-arrow.next {
        right: 20px;
    }

    /* dot indicators in lightbox */
    .lb-dots {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 7px;
        z-index: 10;
    }

    .lb-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .35);
        border: none;
        cursor: pointer;
        transition: background .15s, transform .15s;
    }

    .lb-dot.active {
        background: #ffc700;
        transform: scale(1.25);
    }

    @media (max-width: 576px) {
        .product-gallery {
            flex-direction: column-reverse;
        }

        .thumb-gallery {
            flex-direction: row;
            width: 100%;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .thumb-image {
            width: 64px;
            height: 64px;
            flex-shrink: 0;
        }
    }
</style>

@php
    $images = $item->image ?? [];
    if (!is_array($images)) { $images = [$images]; }
    $mainImage = $images[0] ?? '';
@endphp

<div class="product-gallery">

    {{-- Thumbnail strip --}}
    <div class="thumb-gallery" id="thumbGallery">
        @foreach($images as $i => $img)
            <img
                src="{{ asset('uploads/items/'.$img) }}"
                class="thumb-image {{ $i === 0 ? 'active' : '' }}"
                alt="View {{ $i+1 }}"
                onclick="changeImage({{ $i }})"
            >
        @endforeach
    </div>

    {{-- Main image --}}
    <div class="main-gallery-image">

        <img
            id="mainProductImage"
            src="{{ asset('uploads/items/'.$mainImage) }}"
            alt="{{ $item->title }}"
            data-bs-toggle="modal"
            data-bs-target="#imageModal"
        >

        <span class="img-count-badge" id="imgCountBadge">1 / {{ count($images) }}</span>

        <span class="img-zoom-hint">
            <i class="bi bi-zoom-in"></i> Zoom
        </span>

        @if(count($images) > 1)
            <button class="img-arrow prev" onclick="changeImage(window._activeImg - 1)">&#8249;</button>
            <button class="img-arrow next" onclick="changeImage(window._activeImg + 1)">&#8250;</button>
        @endif

    </div>

</div>

{{-- Lightbox modal --}}
<div class="modal fade image-modal" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">

            <button class="image-close" data-bs-dismiss="modal" title="Close">&#215;</button>

            <div class="modal-body">
                <img id="modalProductImage" src="{{ asset('uploads/items/'.$mainImage) }}" alt="{{ $item->title }}">

                @if(count($images) > 1)
                    <button class="lb-arrow prev" onclick="lbNav(-1)">&#8249;</button>
                    <button class="lb-arrow next" onclick="lbNav(1)">&#8250;</button>

                    <div class="lb-dots" id="lbDots">
                        @foreach($images as $i => $img)
                            <button class="lb-dot {{ $i===0 ? 'active' : '' }}"
                                onclick="changeImage({{ $i }})"></button>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
<script>
    window._images = @json(array_values($images));
    window._activeImg = 0;
    const _base = '{{ asset('uploads/items/') }}/';

    function changeImage(index) {
        const total = window._images.length;
        index = ((index % total) + total) % total;   // wrap around
        window._activeImg = index;

        const src = _base + window._images[index];
        const mainImg = document.getElementById('mainProductImage');

        // fade transition
        mainImg.classList.add('fading');
        setTimeout(() => {
            mainImg.src = src;
            mainImg.classList.remove('fading');
        }, 180);

        // sync modal
        document.getElementById('modalProductImage').src = src;

        // counter badge
        document.getElementById('imgCountBadge').textContent =
            `${index + 1} / ${total}`;

        // active thumb
        document.querySelectorAll('.thumb-image').forEach((t, i) => {
            t.classList.toggle('active', i === index);
        });

        // lightbox dots
        document.querySelectorAll('.lb-dot').forEach((d, i) => {
            d.classList.toggle('active', i === index);
        });
    }

    function lbNav(dir) {
        changeImage(window._activeImg + dir);
    }

    // keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('imageModal');
        if (!modal.classList.contains('show')) return;
        if (e.key === 'ArrowRight') lbNav(1);
        if (e.key === 'ArrowLeft')  lbNav(-1);
        if (e.key === 'Escape')     bootstrap.Modal.getInstance(modal)?.hide();
    });

    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value) + delta;
        const max = parseInt(input.max);
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
    }
</script>
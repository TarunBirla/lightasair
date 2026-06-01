{{-- resources/views/front/about.blade.php --}}
@extends('front.layouts.app')

@section('content')

    <style>
        /* ── HERO CAROUSEL ── */
        .hero-section {
            position: relative;
        }

        .carousel-item {
            position: relative;
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 580px;
            object-fit: cover;
            filter: brightness(.55);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, .65) 0%, rgba(0, 0, 0, .3) 100%);
        }

        .hero-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            z-index: 2;
        }

        .hero-tag {
            display: inline-block;
            background: var(--brand);
            color: var(--dark);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: .35rem .9rem;
            border-radius: 30px;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero-title span {
            color: var(--brand);
        }

        .hero-sub {
            color: rgba(255, 255, 255, .8);
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 2rem;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-hero-primary {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            font-size: 1rem;
            padding: .75rem 2rem;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-hero-primary:hover {
            background: var(--brand-dk);
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 199, 0, .4);
        }

        .btn-hero-secondary {
            background: transparent;
            color: var(--white);
            font-weight: 600;
            font-size: 1rem;
            padding: .75rem 2rem;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, .4);
            text-decoration: none;
            transition: all .2s;
        }

        .btn-hero-secondary:hover {
            border-color: var(--brand);
            color: var(--brand);
        }

        /* carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, .15);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .2);
            margin: 0 1.5rem;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: var(--brand);
        }

        .carousel-indicators [data-bs-target] {
            background: rgba(255, 255, 255, .5);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: none;
        }

        .carousel-indicators .active {
            background: var(--brand);
            width: 24px;
            border-radius: 4px;
        }

        /* ── STATS STRIP ── */
        .stats-strip {
            background: var(--brand);
            padding: 1.5rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 0 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: var(--dark);
            line-height: 1;
        }

        .stat-label {
            font-size: .78rem;
            font-weight: 600;
            color: rgba(0, 0, 0, .6);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        /* ── CATEGORIES ── */
        .cat-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .cat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--brand);
        }

        .cat-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform .35s;
        }

        .cat-card:hover img {
            transform: scale(1.05);
        }

        .cat-card-body {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cat-card-body h6 {
            font-weight: 700;
            font-size: .93rem;
            color: var(--dark);
            margin: 0;
        }

        .cat-arrow {
            width: 30px;
            height: 30px;
            background: var(--brand-lt);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-size: .75rem;
            transition: background .2s;
        }

        .cat-card:hover .cat-arrow {
            background: var(--brand);
        }

        /* ── ITEM CARDS ── */
        .item-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform .25s, box-shadow .25s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .item-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .item-card .img-wrap {
            overflow: hidden;
            position: relative;
        }

        .item-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            transition: transform .4s;
        }

        .item-card:hover img {
            transform: scale(1.06);
        }

        .item-badge {
            position: absolute;
            top: .75rem;
            left: .75rem;
            background: var(--brand);
            color: var(--dark);
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .25rem .65rem;
            border-radius: 20px;
        }

        .item-card-body {
            padding: 1.1rem 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .item-title {
            font-size: .97rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: .4rem;
            line-height: 1.3;
        }

        .item-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
        }

        .item-price small {
            font-size: .75rem;
            font-weight: 500;
            color: var(--muted);
        }

        .qty-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #f0fdf4;
            color: var(--success);
            font-size: .75rem;
            font-weight: 600;
            padding: .25rem .65rem;
            border-radius: 20px;
            border: 1px solid #bbf7d0;
        }

        .item-card-footer {
            padding: .85rem 1.2rem;
            background: var(--light-bg);
            border-top: 1px solid var(--border);
            display: flex;
            gap: .6rem;
        }

        .btn-view {
            flex: 1;
            background: transparent;
            border: 1.5px solid var(--brand);
            color: var(--dark);
            font-weight: 600;
            font-size: .83rem;
            border-radius: 8px;
            padding: .5rem;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            transition: all .2s;
        }

        .btn-view:hover {
            background: var(--brand-lt);
            border-color: var(--brand-dk);
            color: var(--dark);
        }

        .btn-cart {
            flex: 1;
            background: var(--brand);
            border: none;
            color: var(--dark);
            font-weight: 700;
            font-size: .83rem;
            border-radius: 8px;
            padding: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-cart:hover {
            background: var(--brand-dk);
            box-shadow: 0 4px 14px rgba(255, 199, 0, .35);
        }

        /* ── HOW IT WORKS ── */
        .how-section {
            background: var(--light-bg);
        }

        .step-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .step-num {
            width: 60px;
            height: 60px;
            background: var(--brand);
            color: var(--dark);
            border-radius: 50%;
            font-size: 1.5rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .step-card h5 {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: .5rem;
        }

        .step-card p {
            color: var(--muted);
            font-size: .85rem;
            line-height: 1.6;
        }

        /* ── CTA ── */
        .cta-section {
            background: var(--brand);
            padding: 4rem 0;
        }

        .marquee-wrapper {
            width: 100%;
            background: var(--dark);
            overflow: hidden;
            padding: 12px 0;
            /* border-top: 2px solid #000; */
            /* border-bottom: 2px solid #000; */
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: scroll 20s linear infinite;
        }

        .marquee-text {
            color:var(--brand);
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            padding-right: 80px;
        }

        @keyframes scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .hero-sectiontext{
    background:var(--brand);
    min-height:60vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:40px 20px;
}

.hero-sectiontext h1{
    color:#000;
    font-size:10rem;
    font-weight:700;
    text-transform:uppercase;
    margin:0;
    line-height:1;
}

.hero-sectiontext p{
    color:#000;
    font-size:40px;
    font-weight:400;
    max-width:1000px;
    margin:25px auto;
    line-height:1.4;
}
     .hero-sectiontext1{
    background:var(--brand);
    min-height:60vh;
    display:flex;
    flex-direction:column;
    justify-content:left;
    align-items:left;
    text-align:left;
    padding:40px 20px;
}
.hero-sectiontext1 h2{
    color:#000;
    font-size:3rem;
    font-weight:700;
    text-transform:uppercase;
    margin:0;
    line-height:1;
    margin:25px auto;

}

.hero-sectiontext1 span{
    color:#000;
    font-size:20px;
    font-weight:400;
    max-width:1000px;
    margin:25px auto;
    line-height:1.4;
}

.whatsapp-btn{
    background:#000;
    color:var(--brand);
    text-decoration:none;
    padding:18px 40px;
    border-radius:50px;
    font-size:22px;
    font-weight:700;
    transition:.3s;
}

.whatsapp-btn:hover{
    background:#222;
    transform:translateY(-3px);
}

@media(max-width:768px){

    .hero-section h1{
        font-size:42px;
    }

    .hero-sectiontext p{
        font-size:20px;
    }

    .whatsapp-btn{
        font-size:18px;
        padding:15px 30px;
    }
}
    </style>

    <div class="marquee-wrapper">
        <div class="marquee-track">
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>

            <!-- Duplicate for seamless loop -->
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
            <div class="marquee-text">
                ✦ BEST FILM & TELEVISION LIGHTING RENTAL ✨
            </div>
        </div>
    </div>

    <section class="hero-sectiontext">
    
    <h1>LIGHT AS AIR.</h1>

    <p>
        London's Premier Film & Television Lighting Rental
        for Productions Across the UK
    </p>

    <a href="https://wa.me/447825706997" target="_blank" class="whatsapp-btn">
        CHAT WITH US
    </a>

</section>

    <!-- ── STATS STRIP ── -->
    <section class="stats-strip">
        <div class="container">
            <div class="row g-3 justify-content-center text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Equipment Items</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">12k+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Customer Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <section class="hero-sectiontext1">
    
    <h2>Who we are and whaT WE DO</h2>

    <span>
    
Across London and the wider United Kingdom, film and television lighting hire has matured into a highly specialised and technically advanced sector. Today’s leading rental houses support productions of every scale—from high-end television drama and feature films to commercials, music videos, documentaries, corporate content, and live events. As production standards continue to rise, so too does the demand for reliable service, expertly maintained equipment, and crews who understand the pace and pressure of modern sets.
<br/>
<br/>
What distinguishes truly exceptional providers within the film and television industry is not just their inventory, but their depth of experience and understanding of production workflows. With over a decade of hands-on industry knowledge, a strong family-run ethos, and a highly skilled in-house team, the best companies deliver a level of service built on trust, precision, and consistency. Whether supporting major feature films, high-end television (HETV), or independent productions, the right lighting partner becomes an integral part of the creative process—offering tailored solutions that elevate every frame.
<br/>
<br/>

In today’s industry, professional lighting and grip rental extends far beyond supplying equipment. Leading companies provide comprehensive support services, including experienced gaffers, lighting technicians, and production-ready crews. Many also offer dedicated gaffer diary management, ensuring productions can secure the right talent alongside the right kit—streamlining logistics and enabling a seamless workflow from prep through to wrap.
<br/>
<br/>

Modern lighting inventories are driven by the latest advancements in LED technology, offering energy-efficient, versatile, and highly reliable alternatives to traditional fixtures. With battery-powered options, compatibility with standard domestic power sources, and low heat output, these systems are ideally suited to both large-scale studio environments and confined location shoots. Their flexibility allows crews to work faster, safer, and more creatively in any setting.
<br/>
<br/>

Crucially, today’s LED fixtures deliver exceptional colour accuracy and consistency, with precise control over colour temperature and output. From lightweight, flexible panel systems to powerful Fresnel units, a carefully curated selection of cutting-edge lighting tools ensures cinematographers and gaffers can achieve their vision with confidence. In an industry where precision and reliability are paramount, these advancements continue to redefine what’s possible on set.
    </span>

   

</section>

   

@endsection
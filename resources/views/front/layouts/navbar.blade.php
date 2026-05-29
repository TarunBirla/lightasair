{{-- resources/views/front/layouts/navbar.blade.php --}}

<style>
    /* ── TOPBAR ── */
    .topbar {
        background: var(--brand);
        color: var(--dark);
        font-size: .75rem;
        font-weight: 600;
        padding: .35rem 0;
    }
    .topbar a { color: var(--dark); text-decoration: none; }
    .topbar a:hover { text-decoration: underline; }

    /* ── NAVBAR ── */
    .site-navbar {
        background: var(--dark);
        padding: .75rem 0;
        position: sticky;
        top: 0;
        z-index: 1030;
        box-shadow: 0 2px 16px rgba(0,0,0,.25);
    }

    .site-navbar .navbar-brand {
        font-size: 1.55rem;
        font-weight: 900;
        color: var(--white) !important;
        letter-spacing: -.02em;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .site-navbar .navbar-brand span {
        color: var(--brand);
    }
    .brand-icon {
        width: 36px;
        height: 36px;
        background: var(--brand);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: var(--dark);
    }

    /* nav links */
    .site-navbar .nav-link {
        color: rgba(255,255,255,.75) !important;
        font-size: .88rem;
        font-weight: 500;
        padding: .4rem .75rem !important;
        border-radius: 8px;
        transition: background .2s, color .2s;
        display: flex;
        align-items: center;
        gap: .35rem;
    }
    .site-navbar .nav-link:hover,
    .site-navbar .nav-link.active {
        color: var(--dark) !important;
        background: var(--brand);
    }

    /* cart badge */
    .cart-wrap {
        position: relative;
    }
    .cart-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: var(--brand);
        color: var(--dark);
        font-size: .6rem;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    /* auth buttons */
    .btn-nav-login {
        border: 1.5px solid rgba(255,255,255,.3);
        color: var(--white) !important;
        font-size: .85rem;
        font-weight: 600;
        padding: .4rem 1rem !important;
        border-radius: 8px;
        transition: all .2s;
    }
    .btn-nav-login:hover {
        border-color: var(--brand);
        color: var(--brand) !important;
        background: transparent;
    }
    .btn-nav-register {
        background: var(--brand) !important;
        color: var(--dark) !important;
        font-size: .85rem;
        font-weight: 700;
        padding: .4rem 1rem !important;
        border-radius: 8px;
        border: none;
        transition: all .2s;
    }
    .btn-nav-register:hover {
        background: var(--brand-dk) !important;
        box-shadow: 0 4px 14px rgba(255,199,0,.35);
    }

    /* hamburger */
    .navbar-toggler {
        border: 1.5px solid rgba(255,255,255,.3);
        padding: .3rem .6rem;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255%2c199%2c0%2c1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .divider-nav {
        width: 1px;
        height: 22px;
        background: rgba(255,255,255,.18);
        margin: 0 .3rem;
    }
</style>

<!-- Top Bar -->
<div class="topbar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-telephone-fill me-1"></i>+44 1234 567890</span>
            <span><i class="bi bi-envelope-fill me-1"></i>hello@lightasair.co.uk</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-clock-fill me-1"></i>Mon – Sat: 8am – 7pm</span>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg site-navbar">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="/">
            <div class="brand-icon"><i class="bi bi-box-seam-fill"></i></div>
            Light as<span>AIR</span>
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

                @if(Auth::check())

                    <li class="nav-item">
                        <a class="nav-link cart-wrap" href="/cart">
                            <i class="bi bi-cart3" style="font-size:1.1rem;"></i>
                            Cart
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="cart-count">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/my-bookings">
                            <i class="bi bi-calendar2-check"></i> My Bookings
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>

                    <li><div class="divider-nav d-none d-lg-block"></div></li>

                    <li class="nav-item">
                        <a class="nav-link" href="/logout" style="color:rgba(255,100,100,.8) !important;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>

                @else

                    <li class="nav-item">
                        <a class="nav-link btn-nav-login" href="/login">
                            <i class="bi bi-person"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-register" href="/register">
                            <i class="bi bi-person-plus-fill"></i> Register
                        </a>
                    </li>

                @endif

            </ul>
        </div>

    </div>
</nav>
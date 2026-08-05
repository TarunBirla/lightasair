{{-- resources/views/front/layouts/navbar.blade.php --}}

<style>
    /* ── NAVBAR ── */
    .site-navbar {
        background: #FFC700;
        position: sticky;
        top: 0;
        z-index: 1030;
        border-bottom: 2px solid var(--brand, #000);
        padding: .5rem 0;
    }

    .site-navbar .navbar-brand {
        font-size: 1.5rem;
        font-weight: 900;
        color: #000 !important;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .logoData {
        max-height:100px;
        width: auto;
    }

    /* nav links */
    .site-navbar .nav-link {
        color: #000 !important;
        font-size: .9rem;
        font-weight: 600;
        padding: .45rem .85rem !important;
        border-radius: 8px;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: .4rem;
        border: 1px solid transparent;
    }
    .site-navbar .nav-link:hover {
         background: #e6b200 !important;
        box-shadow: 0 4px 14px rgba(255,199,0,.35);
    }
    .site-navbar .nav-link.active {
        color: #FFC700 !important;
        background: #000 !important;
        font-weight: 700;
    }

    /* cart badge */
    .cart-wrap {
        position: relative;
    }
    .cart-count {
        position: absolute;
        top: -4px;
        right: -4px;
        background: var(--brand, #FFC700);
        color: #111;
        font-size: .65rem;
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
        background: var(--brand, #FFC700) !important;
        color: #111111 !important;
        font-size: .85rem;
        font-weight: 800;
        padding: .45rem 1.1rem !important;
        border-radius: 8px;
        border: none;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-nav-login:hover {
         background: #e6b200 !important;
        box-shadow: 0 4px 14px rgba(255,199,0,.35);
    }
    .btn-nav-register {
        background: var(--brand, #FFC700) !important;
        color: #111111 !important;
        font-size: .85rem;
        font-weight: 800;
        padding: .45rem 1.1rem !important;
        border-radius: 8px;
        border: none;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-nav-register:hover {
        background: #e6b200 !important;
        box-shadow: 0 4px 14px rgba(255,199,0,.35);
    }

    /* user dropdown */
    .user-dropdown-toggle {
        background: rgba(255,255,255,.08);
        border: 1px solid #000;
        color: #000 !important;
        padding: .4rem .85rem !important;
        border-radius: 10px;
        font-size: .88rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .5rem;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }
    .user-dropdown-toggle:hover, .user-dropdown-toggle:focus {
        background: var(--brand, #FFC700);
        color: #111 !important;
        border-color: var(--brand, #FFC700);
    }
    .user-avatar-sm {
        width: 26px;
        height: 26px;
        background:  #000;
        color: #FFC700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        font-weight: 800;
    }
    .dropdown-menu-dark-custom {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 12px;
        padding: .5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,.5);
        margin-top: .5rem;
    }
    .dropdown-menu-dark-custom .dropdown-item {
        color: #ccc;
        font-size: .88rem;
        font-weight: 600;
        padding: .5rem .85rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: .5rem;
        transition: all .15s;
    }
    .dropdown-menu-dark-custom .dropdown-item:hover {
        background: var(--brand, #FFC700);
        color: #111;
    }
    .dropdown-divider-custom {
        border-top: 1px solid #333;
        margin: .4rem 0;
    }

    /* hamburger */
    .navbar-toggler {
        border: 1.5px solid var(--brand, #FFC700);
        padding: .3rem .6rem;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255%2c199%2c0%2c1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
</style>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg site-navbar">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="/">
            <img src="/Logo-3.webp" class="logoData" alt="Light as AIR" onerror="this.outerHTML='<span style=\'color:%23FFC700;font-weight:900;\'>LIGHT AS AIR</span>'">
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('marketplace*') ? 'active' : '' }}" href="/marketplace">
                        <i class="bi bi-shop"></i> Marketplace
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('rentals*') ? 'active' : '' }}" href="/rentals">
                        <i class="bi bi-calendar3"></i> Rentals
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('auctions*') ? 'active' : '' }}" href="/auctions">
                        <i class="bi bi-hammer"></i> Auctions
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link cart-wrap {{ request()->is('cart*') ? 'active' : '' }}" href="/cart">
                        <i class="bi bi-cart3"></i> Cart
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li> -->

                @auth
                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="user-dropdown-toggle dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark-custom">
                            <li>
                                <a class="dropdown-item" href="/my-orders">
                                    <i class="bi bi-bag-check-fill text-warning"></i> My Orders & Purchases
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/my-bookings">
                                    <i class="bi bi-calendar-event-fill text-success"></i> My Rental Bookings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/my-bids">
                                    <i class="bi bi-gavel text-info"></i> My Auction Bids
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/my-invoices">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i> My Invoices
                                </a>
                            </li>

                            @if(Auth::user()->isVendor())
                                <div class="dropdown-divider-custom"></div>
                                <li>
                                    <a class="dropdown-item" href="/vendor/dashboard" style="color:var(--brand, #FFC700);">
                                        <i class="bi bi-speedometer2"></i> Vendor Dashboard
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <div class="dropdown-divider-custom"></div>
                                <li>
                                    <a class="dropdown-item" href="/admin/dashboard" style="color:#60a5fa;">
                                        <i class="bi bi-shield-lock-fill"></i> Admin Panel
                                    </a>
                                </li>
                            @endif

                            <div class="dropdown-divider-custom"></div>
                            <li>
                                <a class="dropdown-item text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Guest Auth Buttons --}}
                    <li class="nav-item ms-lg-2">
                        <a class="btn-nav-login" href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-nav-register" href="/register">
                            <i class="bi bi-person-plus-fill me-1"></i> Register
                        </a>
                    </li>

                     <a href="#items" class="btn-hero-primary">
                                    LIGHT AS AIR
                                </a>
                @endauth

            </ul>
        </div>

    </div>
</nav>
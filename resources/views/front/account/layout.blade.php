@extends('front.layouts.app')

@section('content')

<style>
.account-wrapper {
    padding: 3rem 0 5rem;
    background: #F5F4EF;
    min-height: 85vh;
}
.account-sidebar {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e4df;
    padding: 1.5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.04);
}
.account-user-card {
    text-align: center;
    padding-bottom: 1.2rem;
    border-bottom: 1px solid #f0ede8;
    margin-bottom: 1.2rem;
}
.user-avatar-lg {
    width: 68px;
    height: 68px;
    background: var(--brand, #FFC700);
    color: #111;
    font-size: 1.8rem;
    font-weight: 900;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto .8rem;
    box-shadow: 0 4px 15px rgba(255,199,0,.35);
}
.account-nav {
    display: flex;
    flex-direction: column;
    gap: .4rem;
}
.account-nav-link {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .75rem 1rem;
    border-radius: 10px;
    color: #444;
    font-weight: 600;
    font-size: .92rem;
    text-decoration: none;
    transition: all .2s;
}
.account-nav-link:hover {
    background: #fdfdfa;
    color: #111;
}
.account-nav-link.active {
    background: #111111;
    color: var(--brand, #FFC700);
    font-weight: 700;
}
.account-nav-link i {
    font-size: 1.1rem;
}
</style>

<div class="account-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Left Sidebar -->
            <div class="col-lg-3">
                <div class="account-sidebar">
                    <div class="account-user-card">
                        <div class="user-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <h5 style="font-weight:800;margin:0;color:#111;">{{ Auth::user()->name }}</h5>
                        <div style="font-size:.82rem;color:#888;margin-top:.2rem;">{{ Auth::user()->email }}</div>
                        <span class="badge bg-dark mt-2" style="font-size:.7rem;text-transform:uppercase;">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>

                    <div class="account-nav">
                        <a href="/my-orders" class="account-nav-link {{ request()->is('my-orders*') ? 'active' : '' }}">
                            <i class="bi bi-bag-check-fill"></i> Marketplace Purchases
                        </a>
                        <a href="/my-bookings" class="account-nav-link {{ (request()->is('my-bookings*') || request()->is('my-rentals*')) ? 'active' : '' }}">
                            <i class="bi bi-calendar-event-fill"></i> My Bookings & Rentals
                        </a>
                        <a href="/my-bids" class="account-nav-link {{ request()->is('my-bids*') ? 'active' : '' }}">
                            <i class="bi bi-gavel"></i> Auction Bids
                        </a>
                        <a href="/my-invoices" class="account-nav-link {{ request()->is('my-invoices*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Invoices & Receipts
                        </a>
                        <a href="/profile" class="account-nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                            <i class="bi bi-person-fill"></i> My Profile
                        </a>

                        @if(Auth::user()->isVendor())
                            <hr style="margin:.8rem 0;border-color:#eee;">
                            <a href="/vendor/dashboard" class="account-nav-link" style="color:#d97706;">
                                <i class="bi bi-speedometer2"></i> Vendor Dashboard
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <hr style="margin:.8rem 0;border-color:#eee;">
                            <a href="/admin/dashboard" class="account-nav-link" style="color:#2563eb;">
                                <i class="bi bi-shield-lock-fill"></i> Admin Panel
                            </a>
                        @endif

                        <hr style="margin:.8rem 0;border-color:#eee;">
                        <a href="/logout" class="account-nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Main Content -->
            <div class="col-lg-9">
                @yield('account_content')
            </div>
        </div>
    </div>
</div>
@endsection

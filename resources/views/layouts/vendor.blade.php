<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vendor Dashboard') — Light As Air</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #FFC700;
            --brand-dark: #E6B200;
            --sidebar-bg: #0e0e0e;
            --sidebar-hover: #1a1a1a;
            --sidebar-active-bg: rgba(255,199,0,.12);
            --sidebar-active-text: #FFC700;
            --text-main: #111;
            --text-muted: #888;
            --surface: #fff;
            --bg: #F5F4EF;
            --border: #E5E4DF;
            --radius: 14px;
            --shadow: 0 2px 16px rgba(0,0,0,.07);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            background: var(--bg);
            font-family: 'Akshar', sans-serif;
            color: var(--text-main);
        }

        /* ── SIDEBAR ─────────────────────────── */
        .vendor-sidebar {
            width: 248px;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
            border-right: 1px solid rgba(255,255,255,.05);
        }

        .sidebar-logo {
            padding: 28px 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .logo-badge { display:flex; align-items:center; gap:10px; }

        .logo-icon {
            width:38px; height:38px;
            background: var(--brand);
            border-radius: 10px;
            display:flex; align-items:center; justify-content:center;
            font-size:18px; color:#111;
        }

        .logo-text {
            font-family: 'Akshar', sans-serif;
            font-size: 20px; letter-spacing: 1px;
            color: #fff; line-height: 1;
        }
        .logo-text span { color: var(--brand); }
        .logo-badge-tag {
            font-size: .65rem; background: rgba(255,199,0,.15);
            color: #ffc700; border-radius: 4px;
            padding: 2px 7px; font-weight: 700;
            letter-spacing: .04em; display: block; margin-top: 3px;
        }

        .sidebar-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: rgba(255,255,255,.3);
            padding: 18px 22px 8px;
        }

        .sidebar-nav { flex: 1; padding: 8px 12px; }

        .nav-item { margin-bottom: 2px; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            color: rgba(255,255,255,.55); font-size: .9rem;
            font-weight: 600; text-decoration: none;
            transition: all .2s ease;
        }

        .nav-link i { font-size: 1.05rem; width: 20px; }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-link.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        .nav-badge {
            margin-left: auto;
            background: #ffc700; color: #111;
            border-radius: 10px; font-size: .7rem;
            font-weight: 800; padding: 1px 7px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        /* ── MAIN CONTENT ────────────────────── */
        .vendor-main {
            margin-left: 248px;
            min-height: 100vh;
        }

        /* ── TOPBAR ──────────────────────────── */
        .vendor-topbar {
            background: #fff;
            padding: 0 28px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 0 rgba(0,0,0,.04);
        }

        .topbar-left { display:flex; align-items:center; gap:14px; }

        .page-title {
            font-size: 1.05rem; font-weight: 800; color: #111;
        }

        .topbar-right { display:flex; align-items:center; gap:14px; }

        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--brand); display:flex;
            align-items:center; justify-content:center;
            font-weight:800; font-size:.85rem; color:#111;
        }

        .topbar-user { font-weight:700; font-size:.88rem; color:#111; }
        .topbar-role { font-size:.72rem; color:#888; }

        /* ── CONTENT AREA ────────────────────── */
        .vendor-content { padding: 28px; }

        /* ── STAT CARDS ──────────────────────── */
        .stat-card {
            background: #fff; border-radius: var(--radius);
            padding: 1.4rem 1.5rem; border: 1px solid var(--border);
            box-shadow: var(--shadow); transition: transform .2s;
            position: relative; overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card-accent {
            position: absolute; top: 0; right: 0;
            width: 4px; height: 100%;
        }
        .stat-card-label { font-size: .78rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; margin-bottom:.5rem; }
        .stat-card-value { font-size: 1.9rem; font-weight:800; color:#111; line-height:1; }
        .stat-card-sub { font-size:.78rem; color:#888; margin-top:.4rem; }
        .stat-card-icon {
            position:absolute; right:1.2rem; top:50%; transform:translateY(-50%);
            font-size:2.5rem; opacity:.08;
        }

        /* ── ALERTS ──────────────────────────── */
        .alert-info-custom {
            background: linear-gradient(135deg,#fff8e1,#fffde7);
            border: 1px solid #ffe082; border-radius:12px;
            padding: 1rem 1.2rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:.8rem;
            font-size:.88rem; color:#795548;
        }

        /* ── TABLES ──────────────────────────── */
        .vendor-table { width:100%; border-collapse:collapse; }
        .vendor-table th {
            font-size:.75rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.05em; color:#888; padding:.7rem 1rem;
            border-bottom:2px solid var(--border); text-align:left;
        }
        .vendor-table td { padding:.85rem 1rem; border-bottom:1px solid #f5f5f5; font-size:.88rem; vertical-align:middle; }
        .vendor-table tr:last-child td { border-bottom:none; }
        .vendor-table tr:hover td { background:#fafafa; }

        /* ── BADGES ──────────────────────────── */
        .badge-approved  { background:#d4edda; color:#155724; }
        .badge-pending   { background:#fff3cd; color:#856404; }
        .badge-rejected  { background:#f8d7da; color:#721c24; }
        .badge-active    { background:#cfe2ff; color:#084298; }
        .status-badge    { padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }

        /* ── CARDS ───────────────────────────── */
        .content-card {
            background:#fff; border-radius:var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }
        .content-card-header {
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between;
        }
        .content-card-title { font-weight:800; font-size:1rem; }
        .content-card-body  { padding: 1.4rem; }

        /* ── BTN ─────────────────────────────── */
        .btn-brand {
            background: var(--brand); color:#111;
            font-weight:700; border:none; border-radius:10px;
            padding:.55rem 1.2rem; font-size:.85rem;
            text-decoration:none; display:inline-flex;
            align-items:center; gap:.4rem; transition:all .2s;
        }
        .btn-brand:hover { background:var(--brand-dark); color:#111; transform:translateY(-1px); }

        .btn-outline-brand {
            background:transparent; color:var(--brand);
            border:1.5px solid var(--brand); font-weight:700;
            border-radius:10px; padding:.5rem 1.1rem;
            font-size:.82rem; text-decoration:none;
            display:inline-flex; align-items:center; gap:.4rem;
            transition:all .2s;
        }
        .btn-outline-brand:hover { background:var(--brand); color:#111; }

        @media (max-width:768px) {
            .vendor-sidebar { transform: translateX(-100%); }
            .vendor-main    { margin-left: 0; }
        }
    </style>

    @yield('styles')
</head>
<body>

<!-- ═══ SIDEBAR ═══════════════════════════════════════════ -->
<aside class="vendor-sidebar">

    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon"><i class="bi bi-lightning-fill"></i></div>
            <div>
                <div class="logo-text">Light <span>AsAir</span></div>
                <span class="logo-badge-tag">VENDOR PORTAL</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="sidebar-label">Overview</div>

        <div class="nav-item">
            <a href="/vendor/dashboard" class="nav-link {{ request()->is('vendor/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </div>

        <div class="sidebar-label">Listings</div>

        <div class="nav-item">
            <a href="/vendor/products" class="nav-link {{ request()->is('vendor/products*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Sell Listings
            </a>
        </div>

        <div class="nav-item">
            <a href="/vendor/rentals" class="nav-link {{ request()->is('vendor/rentals*') ? 'active' : '' }}">
                <i class="bi bi-calendar3-fill"></i> Rental Listings
            </a>
        </div>

        <div class="nav-item">
            <a href="/vendor/auctions" class="nav-link {{ request()->is('vendor/auctions*') ? 'active' : '' }}">
                <i class="bi bi-hammer"></i> Auctions
            </a>
        </div>

        <div class="sidebar-label">Business</div>

        <div class="nav-item">
            <a href="/vendor/orders" class="nav-link {{ request()->is('vendor/orders*') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> Orders
            </a>
        </div>

        <div class="nav-item">
            <a href="/vendor/payouts" class="nav-link {{ request()->is('vendor/payouts*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Payouts
            </a>
        </div>

        <div class="nav-item">
            <a href="/vendor/reviews" class="nav-link {{ request()->is('vendor/reviews*') ? 'active' : '' }}">
                <i class="bi bi-star-fill"></i> Reviews
            </a>
        </div>

        <div class="sidebar-label">Account</div>

        <div class="nav-item">
            <a href="/vendor/profile" class="nav-link {{ request()->is('vendor/profile*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Profile & Settings
            </a>
        </div>

        <div class="nav-item">
            <a href="/" class="nav-link" target="_blank">
                <i class="bi bi-shop"></i> View Marketplace
            </a>
        </div>

    </nav>

    <div class="sidebar-footer">
        <a href="/logout" class="nav-link" style="color:rgba(255,255,255,.4);">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>

</aside>

<!-- ═══ MAIN ═══════════════════════════════════════════════ -->
<div class="vendor-main">

    <!-- Topbar -->
    <div class="vendor-topbar">
        <div class="topbar-left">
            <div class="page-title">@yield('page_title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            @if(session('success'))
            <div class="text-success" style="font-size:.85rem;font-weight:700;">
                <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
            </div>
            @endif
            <div class="d-flex align-items-center gap-2">
                <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="topbar-user">{{ auth()->user()->name }}</div>
                    <div class="topbar-role">Vendor Account</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="vendor-content">

        @if(session('error'))
        <div class="alert-info-custom" style="border-color:#f5c6cb;background:#fff5f5;color:#721c24;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

</body>
</html>

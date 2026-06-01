<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Light As AIR — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #FFC700;
            --brand-dark: #E6B200;
            --brand-light: #FFF3B0;
            --sidebar-bg: #111111;
            --sidebar-hover: #1e1e1e;
            --sidebar-active: #FFC700;
            --text-main: #111111;
            --text-muted: #888;
            --surface: #FFFFFF;
            --bg: #F7F6F1;
            --border: #E8E6DF;
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
        }

        /* ── SIDEBAR ─────────────────────────── */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 38px; height: 38px;
            background: var(--brand);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: #111;
        }

        .logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 1px;
            color: #fff;
            line-height: 1;
        }

        .logo-text span {
            color: var(--brand);
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #555;
            padding: 20px 24px 8px;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0 12px;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #aaa;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
        }

        .sidebar nav ul li a i {
            width: 20px;
            font-size: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar nav ul li a:hover {
            background: #1e1e1e;
            color: #fff;
        }

        .sidebar nav ul li a.active,
        .sidebar nav ul li a:focus {
            background: var(--brand);
            color: #111;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #666;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
        }

        .sidebar-footer a:hover {
            background: #1e1e1e;
            color: #ff5555;
        }

        /* ── MAIN ────────────────────────────── */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ──────────────────────────── */
        .topbar {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left h4 {
            font-size: 17px;
            font-weight: 600;
            color: var(--text-main);
        }

        .topbar-left .breadcrumb-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-icon-btn {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: var(--bg);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }

        .topbar-icon-btn:hover {
            background: var(--brand);
            border-color: var(--brand);
            color: #111;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 40px;
            padding: 5px 14px 5px 5px;
            cursor: pointer;
        }

        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--brand);
            color: #111;
            font-size: 12px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        /* ── PAGE CONTENT ────────────────────── */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        .alert-success {
            background: #EDFAF0;
            color: #1a7a3a;
            border: 1px solid #b3e6c2;
            border-radius: var(--radius);
            padding: 12px 18px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #FEF0F0;
            color: #c0392b;
            border: 1px solid #fbc8c8;
            border-radius: var(--radius);
            padding: 12px 18px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon"><i class="fa-solid fa-wind"></i></div>
            <div>
                <div class="logo-text">Light As <span>AIR</span></div>
                <div style="font-size:10px;color:#555;letter-spacing:.5px;">Admin Panel</div>
            </div>
        </div>
    </div>

    <div class="sidebar-label">Main Menu</div>

    <nav>
        <ul>
            <li>
                <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/banner" class="{{ request()->is('admin/banner*') ? 'active' : '' }}">
                    <i class="fa-solid fa-image"></i>
                    Banners
                </a>
            </li>
            <li>
                <a href="/admin/category" class="{{ request()->is('admin/category*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i>
                    Categories
                </a>
            </li>
            <li>
                <a href="/admin/items" class="{{ request()->is('admin/items*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open"></i>
                    Items
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-label">Management</div>

    <nav>
        <ul>
            <li>
                <a href="/admin/bookings" class="{{ request()->is('admin/bookings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    Bookings
                </a>
            </li>
            <li>
                <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    Users
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="/admin/logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">

    <div class="topbar">
        <div class="topbar-left">
            <h4>@yield('page-title', 'Dashboard')</h4>
            <div class="breadcrumb-text">@yield('breadcrumb', 'Admin / Overview')</div>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-icon-btn">
                <i class="fa-regular fa-bell" style="font-size:15px"></i>
            </a>
            <div class="user-pill">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
            </div>
        </div>
    </div>

    <div class="page-content">

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
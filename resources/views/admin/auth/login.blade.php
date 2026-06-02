<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Light As AIR — Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #FFC700;
            --brand-dark: #E6B200;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
             font-family: 'Akshar', sans-serif;
            background: #F7F6F1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            display: flex;
            width: 880px;
            min-height: 520px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.10);
        }

        /* ── LEFT PANEL ── */
        .login-brand {
            width: 380px;
            background: #111;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: var(--brand);
            opacity: .12;
        }

        .login-brand::after {
            content: '';
            position: absolute;
            top: -40px; left: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: var(--brand);
            opacity: .07;
        }

        .brand-top {
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 52px; height: 52px;
            background: var(--brand);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            color: #111;
            margin-bottom: 24px;
        }

        .brand-name {
            font-family: 'Akshar', sans-serif;
            font-size: 38px;
            color: #fff;
            line-height: 1;
            margin-bottom: 6px;
        }

        .brand-name span { color: var(--brand); }

        .brand-tagline {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            max-width: 220px;
        }

        .brand-bottom {
            position: relative;
            z-index: 2;
        }

        .brand-stat {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .brand-stat-num {
            font-family: 'Akshar', sans-serif;
            font-size: 42px;
            color: var(--brand);
            line-height: 1;
        }

        .brand-stat-label {
            font-size: 13px;
            color: #555;
        }

        /* ── RIGHT PANEL ── */
        .login-form-panel {
            flex: 1;
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-panel h2 {
            font-size: 24px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        .login-form-panel p {
            font-size: 14px;
            color: #888;
            margin-bottom: 36px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #E8E6DF;
            border-radius: 10px;
            font-size: 14px;
             font-family: 'Akshar', sans-serif;
            color: #111;
            background: #FAFAF8;
            outline: none;
            transition: border-color .2s, background .2s;
        }

        .form-control:focus {
            border-color: var(--brand);
            background: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--brand);
            border: none;
            border-radius: 10px;
             font-family: 'Akshar', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: #111;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-danger {
            background: #FEF0F0;
            color: #c0392b;
            border: 1.5px solid #fbc8c8;
            border-radius: 10px;
            padding: 11px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-brand">
        <div class="brand-top">
            <div class="brand-icon">
                <i class="fa-solid fa-wind"></i>
            </div>
            <div class="brand-name">Light As <span>AIR</span></div>
            <div class="brand-tagline">Rental management made effortless. Clean, fast, reliable.</div>
        </div>
        <div class="brand-bottom">
            <div class="brand-stat">
                <div class="brand-stat-num">100%</div>
                <div class="brand-stat-label">Uptime guaranteed</div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-form-panel">

        <h2>Welcome back 👋</h2>
        <p>Sign in to your admin account</p>

        @if(session('error'))
            <div class="alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="admin@example.com"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="••••••••"
                        required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i>
                Sign In
            </button>

        </form>

    </div>

</div>

</body>
</html>
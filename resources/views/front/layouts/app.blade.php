<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentEase – Premium Equipment Rental</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brand:    #FFC700;
            --brand-dk: #e6b200;
            --brand-lt: #fff8dc;
            --dark:     #111111;
            --mid:      #222222;
            --muted:    #555555;
            --light-bg: #fafafa;
            --border:   #e8e8e8;
            --white:    #ffffff;
            --success:  #22c55e;
            --danger:   #ef4444;
            --radius:   12px;
            --shadow:   0 4px 24px rgba(0,0,0,.08);
            --shadow-lg:0 8px 40px rgba(0,0,0,.13);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background: var(--white);
            margin: 0;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--brand); border-radius: 3px; }

        /* ── UTILITIES ── */
        .btn-brand {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: .55rem 1.4rem;
            transition: background .2s, transform .15s, box-shadow .2s;
        }
        .btn-brand:hover {
            background: var(--brand-dk);
            color: var(--dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,199,0,.35);
        }

        .btn-brand-outline {
            border: 2px solid var(--brand);
            color: var(--dark);
            font-weight: 600;
            border-radius: 8px;
            padding: .5rem 1.3rem;
            background: transparent;
            transition: all .2s;
        }
        .btn-brand-outline:hover {
            background: var(--brand);
            color: var(--dark);
        }

        .badge-brand {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            font-size: .7rem;
            padding: .3em .6em;
            border-radius: 20px;
        }

        /* ── SECTION TITLE ── */
        .section-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--brand);
        }
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

@include('front.layouts.navbar')

<main>
    @yield('content')
</main>

@include('front.layouts.footer')

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
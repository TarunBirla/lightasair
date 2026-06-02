<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Light as AIR – Premium Equipment Rental</title>

    <!-- Bootstrap 5 -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            --heading-1-size-value: 5;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Akshar', sans-serif;
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
        .whatsapp-float{
    position: fixed;
    right: 20px;
    bottom: 25px;
    width: 65px;
    height: 65px;
    background: #25D366;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 34px;
    z-index: 9999;
    box-shadow: 0 8px 25px rgba(37,211,102,.45);
    transition: all .3s ease;
}

.whatsapp-float:hover{
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 10px 30px rgba(37,211,102,.6);
}

.whatsapp-float::before{
    content:'';
    position:absolute;
    width:100%;
    height:100%;
    border-radius:50%;
    background:#25D366;
    animation: whatsapp-pulse 2s infinite;
    z-index:-1;
}

.whatsapp-float1{
    position: fixed;
    right: 20px;
    bottom: 100px;
    width: 65px;
    height: 65px;
    background: var(--brand);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 34px;
    z-index: 9999;
    box-shadow: 0 8px 25px var(--brand);
    transition: all .3s ease;
}

.whatsapp-float1:hover{
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 10px 30px var(--brand);
}

.whatsapp-float1::before{
    content:'';
    position:absolute;
    width:100%;
    height:100%;
    border-radius:50%;
    background:#var(--brand);
    animation: whatsapp-pulse 2s infinite;
    z-index:-1;
}

@keyframes whatsapp-pulse{
    0%{
        transform:scale(1);
        opacity:.7;
    }
    70%{
        transform:scale(1.5);
        opacity:0;
    }
    100%{
        transform:scale(1.5);
        opacity:0;
    }
}

@media(max-width:768px){
    .whatsapp-float{
        width:55px;
        height:55px;
        font-size:28px;
        right:15px;
        bottom:20px;
    }
}
.request-count{
    position:absolute;
    top:-5px;
    right:-5px;
    background:red;
    color:#fff;
    width:24px;
    height:24px;
    border-radius:50%;
    font-size:12px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
}
.btn-hero-primary {
            background: var(--brand);
            color: var(--dark);
            font-weight: 700;
            font-size: 2rem;
            padding: .75rem 2rem;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
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

<a href="javascript:void(0)"
   onclick="openRequestModal()"
   class="whatsapp-float1">

    <div class="d-flex flex-column align-items-center">
        <!-- <i class="bi bi-whatsapp"></i> -->
        <small style="font-size:16px;">Request</small>
    </div>

    <span id="requestCount" class="request-count">
        0
    </span>
</a>

<a href="https://wa.me/447825706997"
   target="_blank"
   class="whatsapp-float">

    <i class="bi bi-whatsapp"></i>

</a>
<script>
    function openRequestModal()
{
    let requests =
    JSON.parse(localStorage.getItem('requests')) || [];

    if(requests.length === 0)
    {
        showToast('Please select item first');
        return;
    }

    new bootstrap.Modal(
        document.getElementById('requestModal')
    ).show();
}
</script>

<script>
function addToRequest(id,title)
{
    let requests =
    JSON.parse(localStorage.getItem('requests')) || [];

    let exists =
    requests.find(x => x.id == id);

    if(exists)
    {
        showToast('Already added');
        return;
    }

    requests.push({
        id:id,
        title:title
    });

    localStorage.setItem(
        'requests',
        JSON.stringify(requests)
    );

    updateRequestCount();

    showToast(title + ' added successfully');
}

function updateRequestCount()
{
    let requests =
    JSON.parse(localStorage.getItem('requests')) || [];

    let countElement =
    document.getElementById('requestCount');

    if(countElement)
    {
        countElement.innerHTML = requests.length;
    }
}

document.addEventListener(
'DOMContentLoaded',
function(){
    updateRequestCount();
});
</script>
</body>
</html>
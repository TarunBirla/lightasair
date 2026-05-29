<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f5f6fa;
        }

        .sidebar{
            width:260px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:#1e293b;
            overflow-y:auto;
        }

        .logo{
            padding:20px;
            color:#fff;
            font-size:22px;
            font-weight:700;
            text-align:center;
            border-bottom:1px solid rgba(255,255,255,.1);
        }

        .sidebar ul{
            list-style:none;
            padding:0;
            margin:0;
        }

        .sidebar ul li a{
            display:block;
            color:#cbd5e1;
            text-decoration:none;
            padding:14px 20px;
            transition:.3s;
        }

        .sidebar ul li a:hover{
            background:#334155;
            color:#fff;
        }

        .sidebar ul li a i{
            width:25px;
        }

        .main-content{
            margin-left:260px;
        }

        .topbar{
            height:70px;
            background:#fff;
            box-shadow:0 2px 10px rgba(0,0,0,.08);
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 25px;
        }

        .page-content{
            padding:25px;
        }

        .card-box{
            background:#fff;
            border-radius:10px;
            padding:20px;
            box-shadow:0 2px 10px rgba(0,0,0,.08);
        }

        .user-box{
            display:flex;
            align-items:center;
            gap:10px;
        }

        .user-circle{
            width:40px;
            height:40px;
            border-radius:50%;
            background:#0d6efd;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">
        RENTAL ADMIN
    </div>

    <ul>

        <li>
            <a href="/admin/dashboard">
                <i class="fa fa-home"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="/admin/banner">
                <i class="fa fa-image"></i>
                Banner
            </a>
        </li>

        <li>
            <a href="/admin/category">
                <i class="fa fa-list"></i>
                Categories
            </a>
        </li>

        <li>
            <a href="/admin/items">
                <i class="fa fa-box"></i>
                Items
            </a>
        </li>

        <li>
            <a href="/admin/bookings">
                <i class="fa fa-calendar"></i>
                Bookings
            </a>
        </li>

        <li>
            <a href="/admin/users">
                <i class="fa fa-users"></i>
                Users
            </a>
        </li>

       

        <li>
            <a href="/admin/logout">
                <i class="fa fa-sign-out-alt"></i>
                Logout
            </a>
        </li>

    </ul>

</div>


<div class="main-content">

    <div class="topbar">

        <h4 class="mb-0">
            Admin Dashboard
        </h4>

        <div class="user-box">

            <div class="user-circle">
                {{ strtoupper(substr(Auth::user()->name ?? 'A',0,1)) }}
            </div>

            <div>
                <strong>
                    {{ Auth::user()->name ?? 'Admin' }}
                </strong>
            </div>

        </div>

    </div>

    <div class="page-content">

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif

        @yield('content')

    </div>

</div>

</body>
</html>
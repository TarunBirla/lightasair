@extends('front.layouts.app')

@section('content')

<style>

.profile-header{
    background:linear-gradient(
    135deg,
    #111827 0%,
    #1f2937 100%);
    padding:60px 0;
}

.profile-card{
    background:#fff;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    overflow:hidden;
}

.profile-avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    background:#ffc700;
    color:#111;
    font-size:50px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
}

.profile-name{
    font-size:28px;
    font-weight:700;
}

.profile-email{
    color:#6b7280;
}

.info-box{
    background:#f9fafb;
    border-radius:12px;
    padding:15px;
    margin-bottom:15px;
}

.info-label{
    font-size:13px;
    color:#6b7280;
    text-transform:uppercase;
    font-weight:600;
}

.info-value{
    font-size:16px;
    font-weight:600;
    color:#111827;
}

.stat-box{
    background:#fff8db;
    border:1px solid #ffe58f;
    border-radius:12px;
    padding:20px;
    text-align:center;
}

.stat-number{
    font-size:28px;
    font-weight:800;
}

.stat-title{
    color:#666;
}

</style>

<div class="profile-header">

<div class="container">

<h2 class="text-white">

My Profile

</h2>

</div>

</div>

<div class="container my-5">

<div class="row">

<div class="col-lg-4">

<div class="profile-card p-4 text-center">

<div class="profile-avatar">

{{ strtoupper(substr(Auth::user()->name,0,1)) }}

</div>

<h3 class="profile-name mt-3">

{{ Auth::user()->name }}

</h3>

<p class="profile-email">

{{ Auth::user()->email }}

</p>

<a
href="/my-bookings"
class="btn btn-warning w-100 mt-3">

My Bookings

</a>

</div>

</div>

<div class="col-lg-8">

<div class="profile-card p-4">

<h4 class="mb-4">

Personal Information

</h4>

<div class="row">

<div class="col-md-6">

<div class="info-box">

<div class="info-label">

Full Name

</div>

<div class="info-value">

{{ Auth::user()->name }}

</div>

</div>

</div>

<div class="col-md-6">

<div class="info-box">

<div class="info-label">

Email Address

</div>

<div class="info-value">

{{ Auth::user()->email }}

</div>

</div>

</div>

<div class="col-md-6">

<div class="info-box">

<div class="info-label">

Mobile Number

</div>

<div class="info-value">

{{ Auth::user()->mobile }}

</div>

</div>

</div>

<div class="col-md-6">

<div class="info-box">

<div class="info-label">

Account Status

</div>

<div class="info-value">

<span class="badge bg-success">

{{ ucfirst(Auth::user()->status) }}

</span>

</div>

</div>

</div>

</div>

<hr>

<div class="row mt-4">

<div class="col-md-4">

<div class="stat-box">

<div class="stat-number">

{{ \App\Models\Booking::where('user_id',Auth::id())->count() }}

</div>

<div class="stat-title">

Total Bookings

</div>

</div>

</div>

<div class="col-md-4">

<div class="stat-box">

<div class="stat-number">

{{ \App\Models\Booking::where('user_id',Auth::id())->where('status','pending')->count() }}

</div>

<div class="stat-title">

Pending

</div>

</div>

</div>

<div class="col-md-4">

<div class="stat-box">

<div class="stat-number">

{{ \App\Models\Booking::where('user_id',Auth::id())->where('status','approved')->count() }}

</div>

<div class="stat-title">

Approved

</div>

</div>

</div>

</div>

<div class="mt-4">

<a
href="/my-bookings"
class="btn btn-dark">

View All Bookings

</a>

<a
href="/logout"
class="btn btn-danger">

Logout

</a>

</div>

</div>

</div>

</div>

</div>

@endsection
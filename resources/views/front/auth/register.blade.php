@extends('front.layouts.app')

@section('content')

<style>
.auth-section {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 3rem 0;
}
.auth-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
}
.auth-card-top {
    background: linear-gradient(135deg, var(--dark) 0%, #2a2a2a 100%);
    padding: 2.2rem 2rem 1.8rem;
    text-align: center;
}
.auth-logo-circle {
    width: 68px;
    height: 68px;
    background: var(--brand);
    border-radius: 50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.8rem;
    color:var(--dark);
    margin:0 auto 1rem;
}
.auth-title {
    font-size:1.5rem;
    font-weight:800;
    color:#fff;
}
.auth-sub {
    color:rgba(255,255,255,.6);
    font-size:.85rem;
}
.auth-body {
    padding:2rem;
}
.form-label {
    font-size:.78rem;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:.4rem;
}
.auth-input {
    width:100%;
    border:1px solid #ddd;
    border-radius:10px;
    padding:.75rem 1rem .75rem 2.7rem;
    background:#fafafa;
}
.auth-input:focus {
    border-color:#ffc700;
    outline:none;
}
.input-icon-wrap {
    position:relative;
    margin-bottom:1rem;
}
.input-icon {
    position:absolute;
    left:.9rem;
    top:50%;
    transform:translateY(-50%);
    color:#999;
}
.btn-auth {
    width:100%;
    border:none;
    border-radius:10px;
    background:#ffc700;
    color:#111;
    font-weight:800;
    padding:.9rem;
}
.auth-footer {
    text-align:center;
    border-top:1px solid #eee;
    padding:1rem;
}
.auth-footer a {
    font-weight:700;
    text-decoration:none;
}
</style>

<div class="auth-section">

<div class="container">

<div class="auth-card">

<div class="auth-card-top">

<div class="auth-logo-circle">

<i class="bi bi-person-plus-fill"></i>

</div>

<h2 class="auth-title">

Create Account

</h2>

<p class="auth-sub">

Register to rent equipment online

</p>

</div>

<div class="auth-body">

@if($errors->any())

<div class="alert alert-danger">

{{ $errors->first() }}

</div>

@endif

<form method="POST" action="/register">

@csrf

<label class="form-label">

Full Name

</label>

<div class="input-icon-wrap">

<i class="bi bi-person-fill input-icon"></i>

<input
type="text"
name="name"
class="auth-input"
placeholder="Enter Full Name"
required>

</div>

<label class="form-label">

Mobile Number

</label>

<div class="input-icon-wrap">

<i class="bi bi-telephone-fill input-icon"></i>

<input
type="text"
name="mobile"
class="auth-input"
placeholder="Enter Mobile Number"
required>

</div>

<label class="form-label">

Email Address

</label>

<div class="input-icon-wrap">

<i class="bi bi-envelope-fill input-icon"></i>

<input
type="email"
name="email"
class="auth-input"
placeholder="Enter Email Address"
required>

</div>

<label class="form-label">

Password

</label>

<div class="input-icon-wrap">

<i class="bi bi-lock-fill input-icon"></i>

<input
type="password"
name="password"
class="auth-input"
placeholder="Enter Password"
required>

</div>



<button
type="submit"
class="btn-auth">

<i class="bi bi-person-check-fill"></i>

Create Account

</button>

</form>

</div>

<div class="auth-footer">

Already have an account?

<a href="/login">

Login Here

</a>

</div>

</div>

</div>

</div>

@endsection
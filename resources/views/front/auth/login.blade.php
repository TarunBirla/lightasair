{{-- resources/views/front/login.blade.php --}}
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
    max-width: 440px;
    margin: 0 auto;
}
.auth-card-top {
    background: linear-gradient(135deg, var(--dark) 0%, #2a2a2a 100%);
    padding: 2.2rem 2rem 1.8rem;
    text-align: center;
}
.auth-logo-circle {
    width: 68px; height: 68px;
    background: var(--brand);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--dark);
    margin: 0 auto 1rem;
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
}
.auth-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--white);
    margin: 0 0 .25rem;
}
.auth-sub {
    font-size: .82rem;
    color: rgba(255,255,255,.5);
}
.auth-body { padding: 2rem; }

.form-label {
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--dark);
    margin-bottom: .4rem;
}
.auth-input {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: .7rem 1rem .7rem 2.6rem;
    font-size: .9rem;
    color: var(--dark);
    background: #fafafa;
    width: 100%;
    transition: border-color .2s, box-shadow .2s;
}
.auth-input:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(255,199,0,.18);
    outline: none;
    background: var(--white);
}
.input-icon-wrap {
    position: relative;
    margin-bottom: 1.2rem;
}
.input-icon {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: .95rem;
    pointer-events: none;
}

.btn-auth {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: var(--brand);
    color: var(--dark);
    font-weight: 800;
    font-size: 1rem;
    padding: .85rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all .2s;
    width: 100%;
    margin-top: .5rem;
}
.btn-auth:hover {
    background: var(--brand-dk);
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
    transform: translateY(-1px);
}

.auth-footer {
    text-align: center;
    padding: 1rem 2rem 1.5rem;
    border-top: 1px solid var(--border);
    font-size: .83rem;
    color: var(--muted);
}
.auth-footer a {
    color: var(--dark);
    font-weight: 700;
    text-decoration: none;
}
.auth-footer a:hover { color: var(--brand-dk); }
</style>

<div class="auth-section">
    <div class="container">
        <div class="auth-card">

            <div class="auth-card-top">
                <div class="auth-logo-circle"><i class="bi bi-person-fill"></i></div>
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-sub">Sign in to manage your rentals</p>
            </div>

            <div class="auth-body">

                @if($errors->any())
                    <div class="mb-3 p-3 rounded-3" style="background:#fff1f2;border:1px solid #fecdd3;color:#9f1239;font-size:.85rem;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <label class="form-label">Email Address</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" name="email" class="auth-input"
                               placeholder="you@example.com"
                               value="{{ old('email') }}" required>
                    </div>

                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password" class="auth-input"
                               placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                Don't have an account? <a href="/register">Create one</a>
            </div>
        </div>
    </div>
</div>

@endsection
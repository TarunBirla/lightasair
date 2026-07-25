@extends('front.layouts.app')

@section('content')

<style>
.auth-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 3rem 0;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #111 100%);
}
.auth-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,.4);
    overflow: hidden;
    width: 100%;
    max-width: 560px;
    margin: 0 auto;
}
.auth-card-top {
    background: linear-gradient(135deg, #111 0%, #222 100%);
    padding: 2.5rem 2rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.auth-card-top::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 140px; height: 140px;
    background: rgba(255,199,0,.08);
    border-radius: 50%;
}
.auth-logo-circle {
    width: 72px; height: 72px;
    background: #ffc700;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.9rem; color: #111;
    margin: 0 auto 1rem;
    box-shadow: 0 4px 20px rgba(255,199,0,.35);
}
.auth-title { font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom:.3rem; }
.auth-sub   { color: rgba(255,255,255,.55); font-size: .85rem; }
.auth-body  { padding: 2rem 2.2rem; }

/* Role Toggle */
.role-toggle { display: flex; gap: .7rem; margin-bottom: 1.6rem; }
.role-btn {
    flex: 1; padding: .85rem .5rem; border: 2px solid #e8e8e8;
    border-radius: 12px; background: #fafafa; cursor: pointer;
    text-align: center; transition: all .25s ease; font-weight: 700;
    font-size: .85rem; color: #555;
}
.role-btn:hover  { border-color: #ffc700; color: #111; }
.role-btn.active { border-color: #ffc700; background: #ffc700; color: #111; box-shadow: 0 4px 15px rgba(255,199,0,.3); }
.role-btn i { display: block; font-size: 1.5rem; margin-bottom: .4rem; }
.role-input { display: none; }

/* Vendor Extra Fields */
.vendor-fields { display: none; }
.vendor-fields.show { display: block; }
.vendor-notice {
    background: linear-gradient(135deg, #fff8e1, #fffde7);
    border: 1px solid #ffe082; border-radius: 10px;
    padding: .9rem 1rem; margin-bottom: 1.2rem;
    font-size: .82rem; color: #795548;
}
.vendor-notice i { color: #ffc107; }

.form-label { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem; color: #555; display: block; }
.auth-input {
    width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px;
    padding: .78rem 1rem .78rem 2.8rem; background: #fafafa;
    font-size: .9rem; transition: border-color .2s, box-shadow .2s;
}
.auth-input:focus { border-color: #ffc700; outline: none; box-shadow: 0 0 0 3px rgba(255,199,0,.15); background: #fff; }
.input-icon-wrap { position: relative; margin-bottom: 1rem; }
.input-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 1rem; }
.input-row { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }

.btn-auth {
    width: 100%; border: none; border-radius: 12px;
    background: linear-gradient(135deg, #ffc700, #ffb300);
    color: #111; font-weight: 800; padding: 1rem;
    font-size: 1rem; cursor: pointer; transition: all .25s;
    box-shadow: 0 4px 15px rgba(255,199,0,.3); margin-top: .5rem;
}
.btn-auth:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,199,0,.4); }

.divider { display: flex; align-items: center; gap: .8rem; margin: 1rem 0; }
.divider hr { flex: 1; border: none; border-top: 1px solid #eee; }
.divider span { font-size: .8rem; color: #aaa; font-weight: 600; }

.auth-footer { text-align: center; border-top: 1px solid #f0f0f0; padding: 1.2rem; background: #fafafa; }
.auth-footer a { font-weight: 700; color: #ffc700; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }

.alert-danger { background: #fff5f5; border: 1px solid #fcc; border-radius: 10px; padding: .8rem 1rem; color: #c00; font-size: .85rem; margin-bottom: 1rem; }
</style>

<div class="auth-section">
<div class="container">
<div class="auth-card">

    <!-- Top header -->
    <div class="auth-card-top">
        <div class="auth-logo-circle"><i class="bi bi-person-plus-fill"></i></div>
        <h2 class="auth-title">Create Your Account</h2>
        <p class="auth-sub">Join the UK's #1 film lighting marketplace</p>
    </div>

    <div class="auth-body">

        @if($errors->any())
        <div class="alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/register" id="registerForm">
        @csrf

        <!-- Role Selection -->
        <label class="form-label mb-2">I want to join as</label>
        <div class="role-toggle mb-3">
            <div class="role-btn active" id="roleCustomerBtn" onclick="selectRole('customer')">
                <i class="bi bi-person-circle"></i>
                Customer
                <small style="display:block;font-weight:400;color:inherit;opacity:.75;margin-top:2px">Browse & Rent</small>
            </div>
            <div class="role-btn" id="roleVendorBtn" onclick="selectRole('vendor')">
                <i class="bi bi-shop"></i>
                Vendor
                <small style="display:block;font-weight:400;color:inherit;opacity:.75;margin-top:2px">Sell & Rent Out</small>
            </div>
        </div>
        <input type="hidden" name="role" id="roleInput" value="customer">

        <!-- Common Fields -->
        <div class="input-row">
            <div>
                <label class="form-label">Full Name</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-person-fill input-icon"></i>
                    <input type="text" name="name" class="auth-input" placeholder="Your full name" value="{{ old('name') }}" required>
                </div>
            </div>
            <div>
                <label class="form-label">Phone Number</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-telephone-fill input-icon"></i>
                    <input type="text" name="phone" class="auth-input" placeholder="07xxx xxxxxx" value="{{ old('phone') }}">
                </div>
            </div>
        </div>

        <label class="form-label">Email Address</label>
        <div class="input-icon-wrap">
            <i class="bi bi-envelope-fill input-icon"></i>
            <input type="email" name="email" class="auth-input" placeholder="you@example.com" value="{{ old('email') }}" required>
        </div>

        <!-- Vendor Extra Fields -->
        <div class="vendor-fields" id="vendorFields">
            <div class="vendor-notice">
                <i class="bi bi-info-circle-fill me-1"></i>
                Vendor accounts require <strong>admin approval</strong> before you can start listing. You'll be notified within 24 hours.
            </div>
            <label class="form-label">Company / Business Name</label>
            <div class="input-icon-wrap">
                <i class="bi bi-building input-icon"></i>
                <input type="text" name="company_name" class="auth-input" placeholder="Your business name" value="{{ old('company_name') }}">
            </div>
        </div>

        <div class="input-row">
            <div>
                <label class="form-label">Password</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password" class="auth-input" placeholder="Min. 6 characters" required>
                </div>
            </div>
            <div>
                <label class="form-label">Confirm Password</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password_confirmation" class="auth-input" placeholder="Repeat password" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-auth" id="registerBtn">
            <i class="bi bi-person-check-fill me-2"></i>
            <span id="registerBtnText">Create Customer Account</span>
        </button>

        </form>
    </div>

    <div class="auth-footer">
        Already have an account? <a href="/login">Sign In Here</a>
    </div>

</div>
</div>
</div>

<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('vendorFields').classList.toggle('show', role === 'vendor');
    document.getElementById('roleCustomerBtn').classList.toggle('active', role === 'customer');
    document.getElementById('roleVendorBtn').classList.toggle('active', role === 'vendor');
    document.getElementById('registerBtnText').textContent =
        role === 'vendor' ? 'Apply as Vendor' : 'Create Customer Account';
}

// Restore role on validation error
@if(old('role') === 'vendor')
    selectRole('vendor');
@endif
</script>

@endsection
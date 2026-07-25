@extends('front.layouts.app')

@section('content')

<style>
.pending-section {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
}
.pending-card {
    background: #fff;
    border-radius: 24px;
    padding: 3.5rem 2.5rem;
    max-width: 580px;
    margin: 0 auto;
    text-align: center;
    box-shadow: 0 30px 70px rgba(0,0,0,.4);
}
.pending-icon-wrap {
    width: 100px; height: 100px;
    background: linear-gradient(135deg, #fff8e1, #ffe082);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.8rem; color: #f59e0b;
    margin: 0 auto 1.8rem;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255,199,0,.4); }
    70%  { box-shadow: 0 0 0 18px rgba(255,199,0,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,199,0,0); }
}
.pending-title { font-size: 1.8rem; font-weight: 800; color: #111; margin-bottom: .5rem; }
.pending-sub   { color: #666; font-size: 1rem; line-height: 1.7; margin-bottom: 2rem; }

.steps-list { text-align: left; background: #f9f9f9; border-radius: 14px; padding: 1.5rem; margin-bottom: 2rem; }
.step-item  { display: flex; align-items: flex-start; gap: 1rem; padding: .7rem 0; border-bottom: 1px solid #eee; }
.step-item:last-child { border-bottom: none; }
.step-num   {
    width: 30px; height: 30px; min-width: 30px;
    background: #ffc700; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .8rem; color: #111;
}
.step-text strong { display: block; font-size: .9rem; color: #111; }
.step-text span   { font-size: .8rem; color: #888; }

.info-chips { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: center; margin-bottom: 1.8rem; }
.info-chip  {
    background: #f3f4f6; border-radius: 20px;
    padding: .35rem .9rem; font-size: .8rem;
    font-weight: 600; color: #555;
    display: flex; align-items: center; gap: .4rem;
}
.info-chip i { color: #ffc700; }

.btn-home {
    display: inline-block; padding: .9rem 2.2rem;
    background: linear-gradient(135deg, #ffc700, #ffb300);
    color: #111; font-weight: 800; border-radius: 12px;
    text-decoration: none; transition: all .25s;
    box-shadow: 0 4px 15px rgba(255,199,0,.3);
}
.btn-home:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,199,0,.4); color: #111; }

.btn-logout {
    display: inline-block; padding: .9rem 2.2rem;
    background: #f5f5f5; color: #555; font-weight: 700;
    border-radius: 12px; text-decoration: none; transition: all .2s; margin-left: .6rem;
}
.btn-logout:hover { background: #eee; color: #333; }
</style>

<div class="pending-section">
<div class="container">
<div class="pending-card">

    <div class="pending-icon-wrap">
        <i class="bi bi-hourglass-split"></i>
    </div>

    <h1 class="pending-title">Application Under Review</h1>
    <p class="pending-sub">
        Thank you for applying as a vendor on <strong>Light As Air Marketplace</strong>!
        Our team is reviewing your application and you'll be notified within <strong>24 hours</strong>.
    </p>

    <div class="info-chips">
        <div class="info-chip"><i class="bi bi-person-circle"></i> {{ $user->name }}</div>
        <div class="info-chip"><i class="bi bi-envelope-fill"></i> {{ $user->email }}</div>
        @if($user->phone)
        <div class="info-chip"><i class="bi bi-telephone-fill"></i> {{ $user->phone }}</div>
        @endif
    </div>

    <div class="steps-list">
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-text">
                <strong>Application Submitted ✅</strong>
                <span>Your vendor application has been received</span>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-text">
                <strong>Admin Review</strong>
                <span>Our team verifies your details (within 24 hours)</span>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-text">
                <strong>Account Approval</strong>
                <span>You'll receive an email when approved</span>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">4</div>
            <div class="step-text">
                <strong>Start Listing Equipment</strong>
                <span>Upload products, set rentals, create auctions</span>
            </div>
        </div>
    </div>

    <div>
        <a href="/" class="btn-home"><i class="bi bi-house-fill me-2"></i>Browse Marketplace</a>
        <a href="/logout" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>

</div>
</div>
</div>

@endsection

@extends('front.account.layout')

@section('title', 'My Account Profile — Light As Air')

@section('account_content')

<style>
.profile-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e4df;
    padding: 2rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.04);
}
.info-box {
    background: #f9fafb;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #f3f4f6;
}
.info-label {
    font-size: .75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .05em;
    margin-bottom: .2rem;
}
.info-value {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
}
.stat-box {
    background: #fff8db;
    border: 1px solid #ffe58f;
    border-radius: 12px;
    padding: 1.2rem;
    text-align: center;
}
.stat-number {
    font-size: 1.8rem;
    font-weight: 900;
    color: #111;
}
.stat-title {
    color: #666;
    font-size: .85rem;
    font-weight: 600;
}
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.6rem;font-weight:900;margin:0;color:#111;">My Profile Details</h2>
        <p style="color:#888;font-size:.88rem;margin:0;">Personal information & account activity summary</p>
    </div>
</div>

<div class="profile-card">
    <h4 style="font-weight:800;margin-bottom:1.5rem;color:#111;">Personal Information</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ Auth::user()->name }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ Auth::user()->phone ?? 'Not specified' }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-label">Account Role & Status</div>
                <div class="info-value">
                    <span class="badge bg-dark me-1">{{ ucfirst(Auth::user()->role) }}</span>
                    <span class="badge bg-success">{{ ucfirst(Auth::user()->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <hr style="border-color:#eee;margin:1.5rem 0;">

    <h4 style="font-weight:800;margin-bottom:1.2rem;color:#111;">Activity Overview</h4>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ Auth::user()->orders()->count() }}</div>
                <div class="stat-title">Marketplace Purchases</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ Auth::user()->rentalBookings()->count() }}</div>
                <div class="stat-title">Rental Bookings</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ Auth::user()->auctionBids()->count() }}</div>
                <div class="stat-title">Auction Bids Placed</div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px 22px 18px;
        border: 1px solid #E8E6DF;
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,.08);
    }

    .stat-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }

    .stat-icon.yellow { background: #FFF3B0; color: #B38A00; }
    .stat-icon.green  { background: #EDFAF0; color: #1a7a3a; }
    .stat-icon.blue   { background: #EAF3FF; color: #1a5fb4; }
    .stat-icon.red    { background: #FEF0F0; color: #c0392b; }

    .stat-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        background: #EDFAF0;
        color: #1a7a3a;
    }

    .stat-card-bottom {}

    .stat-num {
        font-size: 34px;
        font-weight: 700;
        color: #111;
        line-height: 1;
        font-family: 'Akshar', sans-serif;
        letter-spacing: .5px;
    }

    .stat-label {
        font-size: 13px;
        color: #888;
        margin-top: 4px;
    }

    /* Quick Actions */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 14px;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 28px;
    }

    .qa-card {
        background: #fff;
        border: 1px solid #E8E6DF;
        border-radius: 12px;
        padding: 18px 16px;
        text-decoration: none;
        color: #111;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all .2s;
    }

    .qa-card:hover {
        background: #FFC700;
        border-color: #FFC700;
        color: #111;
    }

    .qa-card i {
        font-size: 18px;
        color: #FFC700;
        transition: color .2s;
    }

    .qa-card:hover i { color: #111; }

    /* Recent section */
    .panel {
        background: #fff;
        border: 1px solid #E8E6DF;
        border-radius: 14px;
        overflow: hidden;
    }

    .panel-header {
        padding: 16px 22px;
        border-bottom: 1px solid #F0EEE8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-header h5 {
        font-size: 15px;
        font-weight: 700;
        color: #111;
    }

    .panel-body {
        padding: 18px 22px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #bbb;
        font-size: 14px;
    }

    .empty-state i {
        font-size: 36px;
        display: block;
        margin-bottom: 10px;
    }
</style>

<!-- Stats -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon yellow">
                <i class="fa-solid fa-image"></i>
            </div>
            <span class="stat-badge">Active</span>
        </div>
        <div class="stat-card-bottom">
            <div class="stat-num">{{ $bannerCount }}</div>
            <div class="stat-label">Total Banners</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <span class="stat-badge">Live</span>
        </div>
        <div class="stat-card-bottom">
            <div class="stat-num">{{ $categoryCount }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <span class="stat-badge">Listed</span>
        </div>
        <div class="stat-card-bottom">
            <div class="stat-num">{{ $itemCount }}</div>
            <div class="stat-label">Total Items</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon red">
                <i class="fa-solid fa-users"></i>
            </div>
            <span class="stat-badge">Request</span>
        </div>
        <div class="stat-card-bottom">
            <div class="stat-num">{{ $requestCount }}</div>
            <div class="stat-label">Total Requests</div>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<!-- <div class="section-title">Quick Actions</div>
<div class="quick-actions">
    <a href="{{ route('banner.create') }}" class="qa-card">
        <i class="fa-solid fa-plus-circle"></i> Add Banner
    </a>
    <a href="{{ route('category.create') }}" class="qa-card">
        <i class="fa-solid fa-folder-plus"></i> Add Category
    </a>
    <a href="{{ route('items.create') }}" class="qa-card">
        <i class="fa-solid fa-box"></i> Add Item
    </a>
    <a href="/admin/bookings" class="qa-card">
        <i class="fa-solid fa-calendar-check"></i> View Bookings
    </a>
</div> -->

<!-- Panel -->
<!-- <div class="panel">
    <div class="panel-header">
        <h5><i class="fa-solid fa-clock-rotate-left me-2" style="color:#FFC700"></i>Recent Activity</h5>
        <a href="/admin/bookings" style="font-size:13px;color:#888;text-decoration:none;font-weight:600;">View All</a>
    </div>
    <div class="panel-body">
        <div class="empty-state">
            <i class="fa-regular fa-calendar-xmark"></i>
            No recent activity yet
        </div>
    </div>
</div> -->

@endsection
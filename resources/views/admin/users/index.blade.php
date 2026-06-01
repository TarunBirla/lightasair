@extends('layouts.admin')

@section('page-title', 'Users')
@section('breadcrumb', 'Admin / Users')

@section('content')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .page-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: #111;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .total-count {
        display: inline-flex;
        align-items: center;
        background: #FFF3B0;
        color: #B38A00;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        overflow: hidden;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead tr {
        background: #FAFAF8;
        border-bottom: 1px solid #F0EEE8;
    }

    .table-card thead th {
        padding: 13px 18px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #888;
        text-align: left;
        white-space: nowrap;
    }

    .table-card tbody tr {
        border-bottom: 1px solid #F7F6F1;
        transition: background .15s;
    }

    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #FAFAF8; }

    .table-card tbody td {
        padding: 14px 18px;
        font-size: 14px;
        color: #111;
        vertical-align: middle;
    }

    .id-badge {
        display: inline-block;
        background: #F7F6F1;
        color: #888;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .user-avatar-sm {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: #FFF3B0;
        color: #B38A00;
        font-size: 14px;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        border: 2px solid #FFC700;
    }

    .user-info-name {
        font-weight: 600;
        color: #111;
        font-size: 14px;
    }

    .user-info-id {
        font-size: 11px;
        color: #bbb;
        margin-top: 1px;
    }

    .email-cell {
        color: #555;
        font-size: 13px;
    }

    .email-cell i {
        color: #FFC700;
        margin-right: 6px;
        font-size: 13px;
    }

    .mobile-cell i {
        color: #FFC700;
        margin-right: 6px;
        font-size: 13px;
    }

    .mobile-cell {
        color: #555;
        font-size: 13px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active   { background: #EDFAF0; color: #1a7a3a; }
    .badge-inactive { background: #FEF0F0; color: #c0392b; }

    .date-cell {
        font-size: 12px;
        color: #888;
        white-space: nowrap;
    }

    .date-cell strong {
        display: block;
        font-size: 13px;
        color: #555;
        font-weight: 600;
    }

    .empty-row td {
        text-align: center;
        padding: 60px 0;
        color: #bbb;
        font-size: 14px;
    }

    .empty-row i {
        font-size: 36px;
        display: block;
        margin-bottom: 10px;
        color: #e0e0e0;
    }

    /* Search bar */
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-bottom: 1px solid #F0EEE8;
        background: #fff;
        gap: 12px;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        max-width: 280px;
    }

    .search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #ccc;
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border: 1px solid #E8E6DF;
        border-radius: 9px;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        color: #111;
        background: #FAFAF8;
        outline: none;
        transition: border-color .2s;
    }

    .search-input:focus {
        border-color: #FFC700;
        background: #fff;
    }

    .table-info-text {
        font-size: 12px;
        color: #aaa;
        font-weight: 500;
    }
</style>

<div class="page-header">
    <h3>
        <i class="fa-solid fa-users" style="color:#FFC700"></i>
        Users
        <span class="total-count">{{ count($users) }} total</span>
    </h3>
</div>

<div class="table-card">

    <div class="table-toolbar">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-input" placeholder="Search users..." id="userSearch">
        </div>
        <div class="table-info-text">
            Showing all registered users
        </div>
    </div>

    <table id="usersTable">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Status</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td><span class="id-badge">{{ $user->id }}</span></td>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-info-name">{{ $user->name }}</div>
                            <div class="user-info-id">ID #{{ $user->id }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="email-cell">
                        <i class="fa-regular fa-envelope"></i>{{ $user->email }}
                    </span>
                </td>
                <td>
                    <span class="mobile-cell">
                        <i class="fa-solid fa-phone"></i>{{ $user->mobile ?? '—' }}
                    </span>
                </td>
                <td>
                    @if($user->status == 'active')
                        <span class="badge-status badge-active">
                            <i class="fa-solid fa-circle" style="font-size:7px"></i> Active
                        </span>
                    @else
                        <span class="badge-status badge-inactive">
                            <i class="fa-solid fa-circle" style="font-size:7px"></i> Inactive
                        </span>
                    @endif
                </td>
                <td>
                    <div class="date-cell">
                        <strong>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</strong>
                        {{ \Carbon\Carbon::parse($user->created_at)->format('h:i A') }}
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">
                    <i class="fa-solid fa-users-slash"></i>
                    No users registered yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.getElementById('userSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

@endsection
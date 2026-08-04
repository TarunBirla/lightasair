@extends('layouts.admin')
@section('page-title', 'Vendor Payouts')
@section('breadcrumb', 'Admin / Payouts')
@section('content')
<style>
.stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 1.8rem; }
.stat-box { background: #fff; border-radius: 12px; padding: 1.2rem 1.4rem; border: 1px solid #E8E6DF; display: flex; align-items: center; gap: 1rem; }
.stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.stat-icon.blue   { background: #EAF3FF; color: #1a5fb4; }
.stat-icon.yellow { background: #FFF3B0; color: #B38A00; }
.stat-icon.green  { background: #EDFAF0; color: #1a7a3a; }
.stat-num   { font-size: 1.7rem; font-weight: 800; color: #111; line-height: 1; }
.stat-label { font-size: .75rem; color: #888; margin-top: .15rem; }

.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.4rem; align-items: center; }
.toolbar form { display: flex; gap: .6rem; flex-wrap: wrap; }
.f-select { padding: .5rem .85rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .85rem; background: #fff; outline: none; font-family: inherit; }
.btn-go  { padding: .5rem 1.1rem; background: #FFC700; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; }
.btn-clr { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; text-decoration: none; color: #555; }

.admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #E8E6DF; }
.admin-table th { background: #f9f8f4; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #888; padding: .8rem 1rem; text-align: left; border-bottom: 1px solid #E8E6DF; }
.admin-table td { padding: .9rem 1rem; border-bottom: 1px solid #f5f4f0; font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border: none; }

.btn-action { padding: .3rem .75rem; border-radius: 6px; font-size: .75rem; font-weight: 700; text-decoration: none; border: none; display: inline-block; cursor: pointer; }
.btn-process { background: #EAF3FF; color: #1a5fb4; }
.btn-pay { background: #EDFAF0; color: #1a7a3a; }
</style>

<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fa-solid fa-list"></i></div>
        <div><div class="stat-num">{{ $stats['total_payouts'] ?? 0 }}</div><div class="stat-label">Total Payouts</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon yellow"><i class="fa-solid fa-hourglass-half"></i></div>
        <div><div class="stat-num">£{{ number_format($stats['pending_amount'] ?? 0, 2) }}</div><div class="stat-label">Pending Amount</div></div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fa-solid fa-check-double"></i></div>
        <div><div class="stat-num">£{{ number_format($stats['paid_amount'] ?? 0, 2) }}</div><div class="stat-label">Paid Amount</div></div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.payouts.index') }}">
        <select name="status" class="f-select">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        <button type="submit" class="btn-go"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="{{ route('admin.payouts.index') }}" class="btn-clr">Reset</a>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Order #</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payout Date</th>
            <th>Reference</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payouts as $payout)
        <tr>
            <td>
                <div style="font-weight:700;">{{ $payout->vendor->name ?? 'N/A' }}</div>
                <div style="font-size:.75rem;color:#888;">{{ $payout->vendor->email ?? '' }}</div>
            </td>
            <td style="font-family:monospace;font-weight:700;">
                @if($payout->order)
                    <a href="{{ route('admin.orders.show', $payout->order->id) }}" style="color:#111;">{{ $payout->order->order_number }}</a>
                @else
                    N/A
                @endif
            </td>
            <td style="font-weight:800;color:#111;">£{{ number_format($payout->amount, 2) }}</td>
            <td>
                @if($payout->status == 'paid')
                    <span class="badge bg-success">Paid</span>
                @elseif($payout->status == 'processing')
                    <span class="badge bg-info text-dark">Processing</span>
                @else
                    <span class="badge bg-warning text-dark">Pending</span>
                @endif
            </td>
            <td style="color:#666;">{{ $payout->paid_at ? $payout->paid_at->format('d M Y') : '—' }}</td>
            <td style="font-family:monospace;font-size:.8rem;color:#888;">{{ $payout->reference_number ?? '—' }}</td>
            <td>
                @if($payout->status == 'pending')
                    <form action="{{ route('admin.payouts.mark-processing', $payout->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-action btn-process">Process</button>
                    </form>
                @endif
                @if($payout->status == 'pending' || $payout->status == 'processing')
                    <button type="button" class="btn-action btn-pay ms-1" onclick="document.getElementById('payModal-{{$payout->id}}').style.display='flex'">Mark Paid</button>

                    <!-- Modal -->
                    <div id="payModal-{{$payout->id}}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
                        <div style="background:#fff;padding:2rem;border-radius:12px;width:400px;max-width:90vw;">
                            <h5 style="font-weight:800;margin-bottom:1rem;">Mark Payout as Paid</h5>
                            <form action="{{ route('admin.payouts.mark-paid', $payout->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label style="font-size:.8rem;font-weight:700;color:#555;">Reference Number (Optional)</label>
                                    <input type="text" name="reference_number" class="form-control" style="border-radius:8px;" placeholder="Transaction ID...">
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-light" onclick="document.getElementById('payModal-{{$payout->id}}').style.display='none'">Cancel</button>
                                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center p-4 text-muted">No payouts found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">{{ $payouts->withQueryString()->links() }}</div>
@endsection

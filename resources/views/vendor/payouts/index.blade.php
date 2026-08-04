@extends('layouts.vendor')
@section('title', 'Payouts')
@section('content')
<div class="page-header mb-4">
    <h1 class="page-title" style="font-size:1.6rem;font-weight:800;">Payout History</h1>
    <p style="color:#888;font-size:.9rem;margin:0;">Track your earnings and payout statuses</p>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#16a34a;"></div>
            <div class="stat-card-label">Total Earned</div>
            <div class="stat-card-value">£{{ number_format($payouts->where('status', 'paid')->sum('amount'), 2) }}</div>
            <i class="bi bi-wallet2 stat-card-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#f59e0b;"></div>
            <div class="stat-card-label">Pending Balance</div>
            <div class="stat-card-value">£{{ number_format($payouts->where('status', 'pending')->sum('amount'), 2) }}</div>
            <i class="bi bi-hourglass-split stat-card-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#0ea5e9;"></div>
            <div class="stat-card-label">In Processing</div>
            <div class="stat-card-value">£{{ number_format($payouts->where('status', 'processing')->sum('amount'), 2) }}</div>
            <i class="bi bi-arrow-repeat stat-card-icon"></i>
        </div>
    </div>
</div>

<div class="card p-4" style="border-radius:16px;border:1px solid #e5e4df;">
    @if($payouts->isEmpty())
        <div style="text-align:center;padding:3rem 1rem;color:#888;">
            <i class="bi bi-cash-stack" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
            <h4>No payout history yet</h4>
            <p>Your earnings will appear here once orders are placed.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle vendor-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payout Date</th>
                        <th>Reference</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $payout)
                    <tr>
                        <td style="font-weight:700;">
                            @if($payout->order)
                                <a href="{{ route('vendor.orders.show', $payout->order->id) }}" style="color:#111;text-decoration:none;">{{ $payout->order->order_number }}</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="font-weight:800;color:#16a34a;">£{{ number_format($payout->amount, 2) }}</td>
                        <td>
                            @if($payout->status == 'paid')
                                <span class="status-badge bg-success text-white">Paid</span>
                            @elseif($payout->status == 'processing')
                                <span class="status-badge bg-info text-dark">Processing</span>
                            @else
                                <span class="status-badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td style="color:#666;">{{ $payout->paid_at ? $payout->paid_at->format('d M Y') : '—' }}</td>
                        <td style="font-family:monospace;color:#888;">{{ $payout->reference_number ?? '—' }}</td>
                        <td style="font-size:.8rem;color:#888;">{{ $payout->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $payouts->links() }}</div>
    @endif
</div>
@endsection

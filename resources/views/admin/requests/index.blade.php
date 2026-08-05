@extends('layouts.admin')

@section('page-title', 'Customer Product Requests')
@section('breadcrumb', 'Admin / Customer Requests')

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
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8E6DF;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
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

        .table-card tbody tr:last-child {
            border-bottom: none;
        }

        .table-card tbody tr:hover {
            background: #FAFAF8;
        }

        .table-card tbody td {
            padding: 14px 18px;
            font-size: 14px;
            color: #111;
            vertical-align: middle;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            background: #FEF0F0;
            color: #c0392b;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s;
        }

        .btn-del:hover {
            background: #c0392b;
            color: #fff;
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            margin: 25px 0;
        }

        .custom-pagination .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="fa-solid fa-envelope-open-text text-warning me-2"></i> Customer Product Requests</h3>
            <div class="text-muted" style="font-size:.88rem;">Manage inquiries, purchase requests, and rental quotes submitted by customers</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product / Item</th>
                        <th>Product Type</th>
                        <th>Customer Name</th>
                        <th>Contact Email</th>
                        <th>Phone</th>
                        <th>Customer Message</th>
                        <th>Date Submitted</th>
                        <th style="min-width:100px;text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $request->id }}</span></td>
                            <td style="font-weight:700;color:#111;">{{ $request->item_name }}</td>
                            <td>
                                @if($request->product_type === 'rental' || str_contains(strtolower($request->product_type ?? ''), 'rent'))
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5" style="font-size:.78rem;border-radius:20px;font-weight:700;">
                                        <i class="fa-solid fa-calendar-check me-1"></i> Rental
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5" style="font-size:.78rem;border-radius:20px;font-weight:700;">
                                        <i class="fa-solid fa-cart-shopping me-1"></i> Sell
                                    </span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $request->name }}</td>
                            <td><a href="mailto:{{ $request->email }}" class="text-decoration-none" style="color:#2563eb;">{{ $request->email }}</a></td>
                            <td><a href="tel:{{ $request->phone }}" class="text-decoration-none" style="color:#111;font-weight:600;">{{ $request->phone }}</a></td>
                            <td style="max-width:250px;font-size:.85rem;color:#555;">{{ Str::limit($request->message, 80) ?: '—' }}</td>
                            <td style="font-size:.82rem;color:#888;">{{ $request->created_at?->format('d M Y, H:i') ?? '—' }}</td>
                            <td style="text-align:right;">
                                <form action="{{ route('admin.requests.delete', $request->id) }}" method="POST" onsubmit="return confirm('Delete this request?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="9" style="text-align:center;padding:50px 0;color:#aaa;">
                                <i class="fa-solid fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                No product requests received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="custom-pagination p-3">
            {{ $requests->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
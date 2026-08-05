<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Invoice #{{ $booking->booking_ref }} – Light as AIR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f5f7;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #111;
            padding: 2rem 0;
        }
        .invoice-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,.08);
            max-width: 820px;
            margin: 0 auto;
            padding: 3rem;
            border: 1px solid #e5e7eb;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        .invoice-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #111;
            margin: 0;
            letter-spacing: -.02em;
        }
        .invoice-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: #16a34a;
            font-family: monospace;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .info-box h6 {
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            margin-bottom: .6rem;
        }
        .table-invoice {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .table-invoice th {
            background: #f9fafb;
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #4b5563;
            padding: .9rem 1.2rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .table-invoice td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: .92rem;
        }
        .total-box {
            background: #f0fdf4;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #bbf7d0;
            max-width: 340px;
            margin-left: auto;
        }
        .btn-print {
            background: #111;
            color: #FFC700;
            font-weight: 800;
            border: none;
            padding: .6rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-print:hover {
            background: #333;
            color: #FFC700;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Top Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4 max-width-820 mx-auto no-print" style="max-width:820px;">
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm rounded-3 font-weight-bold">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="bi bi-printer-fill me-1"></i> Print / Save PDF Invoice
        </button>
    </div>

    {{-- Invoice Document --}}
    <div class="invoice-card">
        <div class="invoice-header">
            <div>
                <img src="/Logo-3.webp" style="max-height:55px;" alt="Light as AIR" onerror="this.outerHTML='<h2 style=\'font-weight:900;color:%23111;\'>LIGHT AS AIR</h2>'">
                <div style="font-size:.85rem;color:#6b7280;margin-top:.4rem;">
                    Equipment Rental Hire Agreement Invoice
                </div>
            </div>
            <div class="text-end">
                <div class="invoice-title">RENTAL INVOICE</div>
                <div class="invoice-number">{{ $booking->booking_ref }}</div>
                <div style="font-size:.85rem;color:#6b7280;margin-top:.2rem;">
                    Issued: {{ $booking->created_at->format('d M Y') }}
                </div>
                <div class="mt-2">
                    <span class="badge bg-success px-3 py-2" style="font-size:.8rem;">{{ strtoupper($booking->status) }}</span>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h6>Equipment Hirer / Customer</h6>
                <div style="font-weight:700;font-size:1rem;color:#111;">{{ $booking->customer->name ?? 'Customer' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">{{ $booking->customer->email ?? '—' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">Phone: {{ $booking->customer->phone ?? '—' }}</div>
                @if($booking->requires_delivery && $booking->delivery_address)
                    <div style="color:#4b5563;font-size:.85rem;margin-top:.4rem;">
                        <strong>Delivery Address:</strong> {{ $booking->delivery_address }}
                    </div>
                @endif
            </div>

            <div class="info-box text-end">
                <h6>Equipment Owner / Vendor</h6>
                <div style="font-weight:700;font-size:1rem;color:#111;">{{ $booking->vendor->name ?? 'Vendor' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">{{ $booking->vendor->email ?? '—' }}</div>
                @if($booking->vendor->vendorProfile?->business_name)
                    <div style="color:#4b5563;font-size:.88rem;">Business: {{ $booking->vendor->vendorProfile->business_name }}</div>
                @endif
            </div>
        </div>

        <table class="table-invoice">
            <thead>
                <tr>
                    <th>Rental Equipment</th>
                    <th>Hire Duration</th>
                    <th class="text-end">Daily Rate</th>
                    <th class="text-end">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight:700;color:#111;">{{ $booking->listing->title ?? 'Rental Equipment' }}</div>
                        <div style="font-size:.78rem;color:#6b7280;">
                            Qty: {{ $booking->qty ?? 1 }} unit(s)
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:700;color:#111;">{{ $booking->total_days }} day(s)</div>
                        <div style="font-size:.78rem;color:#6b7280;">
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} → {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                        </div>
                    </td>
                    <td class="text-end">£{{ number_format($booking->price_per_day, 2) }}/day</td>
                    <td class="text-end" style="font-weight:800;color:#111;">£{{ number_format($booking->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;color:#4b5563;">
                <span>Hire Subtotal:</span>
                <span>£{{ number_format($booking->subtotal, 2) }}</span>
            </div>
            @if($booking->deposit_amount > 0)
                <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;color:#4b5563;">
                    <span>Security Deposit:</span>
                    <span>£{{ number_format($booking->deposit_amount, 2) }}</span>
                </div>
            @endif
            @if($booking->delivery_fee > 0)
                <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;color:#4b5563;">
                    <span>Delivery Fee:</span>
                    <span>£{{ number_format($booking->delivery_fee, 2) }}</span>
                </div>
            @endif
            <div class="d-flex justify-content-between pt-2 border-top border-success" style="font-size:1.25rem;font-weight:900;color:#111;">
                <span>Total Hire Paid:</span>
                <span style="color:#16a34a;">£{{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        <div style="margin-top:3rem;padding-top:1.5rem;border-top:1px solid #f3f4f6;font-size:.8rem;color:#9ca3af;text-align:center;">
            Thank you for choosing <strong>Light as AIR</strong> – UK's Premier Film & Production Equipment Marketplace.
        </div>
    </div>
</div>

</body>
</html>

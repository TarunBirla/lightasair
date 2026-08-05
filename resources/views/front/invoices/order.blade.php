<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} – Light as AIR</title>
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
            font-size: 2rem;
            font-weight: 900;
            color: #111;
            margin: 0;
            letter-spacing: -.02em;
        }
        .invoice-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: #d97706;
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
            background: #fff8e1;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #ffe082;
            max-width: 320px;
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
                    Official Purchase Order Invoice
                </div>
            </div>
            <div class="text-end">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">#{{ $order->order_number }}</div>
                <div style="font-size:.85rem;color:#6b7280;margin-top:.2rem;">
                    Date: {{ $order->created_at->format('d M Y') }}
                </div>
                <div class="mt-2">
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success px-3 py-2" style="font-size:.8rem;">PAID</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size:.8rem;">UNPAID / PENDING</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h6>Customer Details</h6>
                <div style="font-weight:700;font-size:1rem;color:#111;">{{ $order->customer->name ?? 'Customer' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">{{ $order->customer->email ?? '—' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">Phone: {{ $order->customer->phone ?? '—' }}</div>
                @if($order->shipping_address)
                    <div style="color:#4b5563;font-size:.85rem;margin-top:.4rem;">
                        <strong>Address:</strong> {{ $order->shipping_address }}
                    </div>
                @endif
            </div>

            <div class="info-box text-end">
                <h6>Seller / Vendor</h6>
                <div style="font-weight:700;font-size:1rem;color:#111;">{{ $order->vendor->name ?? 'Light As Air Vendor' }}</div>
                <div style="color:#4b5563;font-size:.88rem;">{{ $order->vendor->email ?? '—' }}</div>
                @if($order->vendor->vendorProfile?->business_name)
                    <div style="color:#4b5563;font-size:.88rem;">Business: {{ $order->vendor->vendorProfile->business_name }}</div>
                @endif
            </div>
        </div>

        <table class="table-invoice">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#111;">{{ $item->product->title ?? 'Marketplace Item' }}</div>
                            <div style="font-size:.78rem;color:#6b7280;">Condition: {{ ucfirst($item->product->condition ?? 'New') }}</div>
                        </td>
                        <td style="font-weight:700;">{{ $item->quantity }}</td>
                        <td class="text-end">£{{ number_format($item->price, 2) }}</td>
                        <td class="text-end" style="font-weight:800;color:#111;">£{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Order details available in summary.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-box">
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;color:#6b7280;">
                <span>Subtotal:</span>
                <span>£{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;color:#6b7280;">
                <span>VAT / Processing:</span>
                <span>Included</span>
            </div>
            <div class="d-flex justify-content-between pt-2 border-top border-warning" style="font-size:1.25rem;font-weight:900;color:#111;">
                <span>Total Amount:</span>
                <span style="color:#16a34a;">£{{ number_format($order->subtotal, 2) }}</span>
            </div>
        </div>

        <div style="margin-top:3rem;padding-top:1.5rem;border-top:1px solid #f3f4f6;font-size:.8rem;color:#9ca3af;text-align:center;">
            Thank you for choosing <strong>Light as AIR</strong> – UK's Premier Film & Production Equipment Marketplace.
        </div>
    </div>
</div>

</body>
</html>

@extends('layouts.admin')

@section('page-title', Str::limit($rental->title, 40))
@section('breadcrumb', 'Admin / Rentals / Detail')

@section('content')

<style>
.back-link { display: inline-flex; align-items: center; gap: .4rem; color: #888; font-size: .85rem; text-decoration: none; margin-bottom: 1.2rem; transition: color .15s; }
.back-link:hover { color: #FFC700; }

.detail-grid { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
@media(max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.card { background: #fff; border-radius: 14px; border: 1px solid #E8E6DF; overflow: hidden; margin-bottom: 1.2rem; }
.card-header {
    padding: 1rem 1.4rem; border-bottom: 1px solid #f0ede8;
    display: flex; align-items: center; justify-content: space-between;
    font-weight: 800; font-size: .95rem;
}
.card-body { padding: 1.4rem; }

/* Gallery */
.main-img { width: 100%; max-height: 360px; object-fit: contain; background: #f8f8f6; display: block; }
.thumbs { display: flex; gap: .5rem; padding: .8rem; overflow-x: auto; background: #fafaf8; border-top: 1px solid #f0ede8; }
.thumb-img { width: 72px; height: 60px; object-fit: cover; border-radius: 6px; border: 2px solid transparent; cursor: pointer; transition: border-color .15s; }
.thumb-img.active, .thumb-img:hover { border-color: #FFC700; }

/* Status Banner */
.status-banner { padding: .9rem 1.2rem; border-radius: 10px; margin-bottom: 1.2rem; font-size: .9rem; font-weight: 600; display: flex; align-items: center; gap: .6rem; }
.sb-pending  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.sb-approved { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.sb-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* Info table */
.info-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
.info-table tr { border-bottom: 1px solid #f5f4f0; }
.info-table tr:last-child { border: none; }
.info-table th { padding: .55rem .5rem; color: #888; font-weight: 600; width: 40%; text-align: left; }
.info-table td { padding: .55rem .5rem; color: #111; font-weight: 700; }

/* Action Buttons */
.actions-bar { display: flex; flex-direction: column; gap: .6rem; }
.btn-main {
    width: 100%; padding: .8rem; border: none; border-radius: 10px;
    font-weight: 800; font-size: .9rem; cursor: pointer; transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    text-decoration: none;
}
.btn-approve-main { background: #16a34a; color: #fff; }
.btn-approve-main:hover { background: #15803d; color: #fff; }
.btn-reject-main  { background: #dc2626; color: #fff; }
.btn-reject-main:hover { background: #b91c1c; color: #fff; }
.btn-feature-main { background: #FFF3B0; color: #B38A00; }
.btn-feature-main:hover { background: #ffe066; color: #B38A00; }
.btn-delete-main  { background: #fee2e2; color: #991b1b; }
.btn-delete-main:hover { background: #fecaca; }

/* Bookings table */
.bk-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.bk-table th { padding: .6rem .8rem; background: #f9f8f4; font-weight: 700; font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #888; border-bottom: 1px solid #e8e6df; text-align: left; }
.bk-table td { padding: .75rem .8rem; border-bottom: 1px solid #f5f4f0; vertical-align: middle; }
.bk-table tr:last-child td { border: none; }

.bk-badge { padding: .2rem .65rem; border-radius: 20px; font-size: .68rem; font-weight: 800; display: inline-block; }
.bk-pending   { background: #fff3cd; color: #856404; }
.bk-confirmed { background: #d4edda; color: #155724; }
.bk-active    { background: #cfe2ff; color: #084298; }
.bk-completed { background: #e9ecef; color: #495057; }
.bk-cancelled { background: #f8d7da; color: #721c24; }

.reject-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; align-items: center; justify-content: center; }
.reject-modal.open { display: flex; }
.reject-box { background: #fff; border-radius: 14px; padding: 2rem; width: 440px; max-width: 95vw; }
.reject-box h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; }
.reject-ta { width: 100%; padding: .7rem; border: 1.5px solid #e8e6e0; border-radius: 8px; font-size: .9rem; min-height: 100px; font-family: inherit; resize: vertical; }
.reject-ta:focus { outline: none; border-color: #FFC700; }
.reject-actions { display: flex; gap: .6rem; margin-top: 1rem; justify-content: flex-end; }
.btn-cancel-small { padding: .5rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; cursor: pointer; font-family: inherit; }
.btn-reject-small { padding: .5rem 1.2rem; background: #dc2626; color: #fff; border: none; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; }
</style>

<a href="/admin/rentals" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Rental Listings</a>

{{-- Status Banner --}}
@if($rental->isPending())
<div class="status-banner sb-pending"><i class="fa-solid fa-clock"></i> This listing is <strong>pending admin review</strong>.</div>
@elseif($rental->isApproved())
<div class="status-banner sb-approved"><i class="fa-solid fa-circle-check"></i> This listing is <strong>live on the marketplace</strong>.</div>
@elseif($rental->isRejected())
<div class="status-banner sb-rejected">
    <i class="fa-solid fa-times-circle"></i> This listing was <strong>rejected</strong>.
    @if($rental->rejection_reason) <br><em>Reason: {{ $rental->rejection_reason }}</em> @endif
</div>
@endif

<div class="detail-grid">

    {{-- LEFT --}}
    <div>
        {{-- Gallery --}}
        <div class="card">
            @if($rental->images && count($rental->images))
                <img src="{{ asset('storage/' . $rental->images[0]) }}" id="mainImg" class="main-img" alt="{{ $rental->title }}">
                @if(count($rental->images) > 1)
                <div class="thumbs">
                    @foreach($rental->images as $i => $img)
                    <img src="{{ asset('storage/' . $img) }}" class="thumb-img {{ $i === 0 ? 'active' : '' }}"
                         onclick="document.getElementById('mainImg').src=this.src; document.querySelectorAll('.thumb-img').forEach(t=>t.classList.remove('active')); this.classList.add('active');">
                    @endforeach
                </div>
                @endif
            @else
                <div style="height:280px;display:flex;align-items:center;justify-content:center;background:#f8f8f6;color:#ccc;flex-direction:column;gap:.5rem;">
                    <i class="fa-solid fa-image" style="font-size:2.5rem;"></i><span>No images</span>
                </div>
            @endif
        </div>

        {{-- Description --}}
        <div class="card">
            <div class="card-header">Description</div>
            <div class="card-body" style="font-size:.9rem;line-height:1.8;color:#444;white-space:pre-line;">{{ $rental->description }}</div>
        </div>

        {{-- Bookings --}}
        <div class="card">
            <div class="card-header">
                <span>Rental Bookings ({{ $rental->rentalBookings->count() }})</span>
            </div>
            <div class="card-body" style="padding:0;">
                @if($rental->rentalBookings->isEmpty())
                    <div style="text-align:center;padding:2rem;color:#aaa;font-size:.85rem;">No bookings yet.</div>
                @else
                <table class="bk-table">
                    <thead><tr>
                        <th>Ref</th><th>Customer</th><th>Dates</th><th>Days</th><th>Total</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    @foreach($rental->rentalBookings as $bk)
                    <tr>
                        <td style="font-family:monospace;font-size:.8rem;font-weight:700;">{{ $bk->booking_ref }}</td>
                        <td>{{ $bk->customer->name ?? '—' }}</td>
                        <td style="font-size:.82rem;">{{ $bk->start_date }} → {{ $bk->end_date }}</td>
                        <td>{{ $bk->total_days }}</td>
                        <td style="font-weight:700;">£{{ number_format($bk->total_amount, 2) }}</td>
                        <td><span class="bk-badge bk-{{ $bk->status }}">{{ ucfirst($bk->status) }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Info --}}
        <div class="card">
            <div class="card-header">{{ $rental->title }}</div>
            <div class="card-body">
                <div style="font-size:1.8rem;font-weight:900;color:#111;margin-bottom:.3rem;">
                    £{{ number_format($rental->price_per_day, 2) }}<small style="font-size:.8rem;font-weight:400;color:#888;">/day</small>
                </div>
                @if($rental->price_per_week)
                <div style="font-size:.9rem;color:#888;margin-bottom:.75rem;">£{{ number_format($rental->price_per_week, 2) }}/week</div>
                @endif

                <table class="info-table">
                    <tr><th>Vendor</th><td>{{ $rental->vendor->name }}</td></tr>
                    <tr><th>Category</th><td>{{ $rental->category->name ?? '—' }}</td></tr>
                    <tr><th>Condition</th><td>{{ ucfirst($rental->condition) }}</td></tr>
                    <tr><th>Brand</th><td>{{ $rental->brand ?? '—' }}</td></tr>
                    <tr><th>Model</th><td>{{ $rental->model_number ?? '—' }}</td></tr>
                    <tr><th>Total Qty</th><td>{{ $rental->total_qty }}</td></tr>
                    <tr><th>Min Days</th><td>{{ $rental->min_rental_days }}</td></tr>
                    <tr><th>Max Days</th><td>{{ $rental->max_rental_days ?? 'No limit' }}</td></tr>
                    <tr><th>Deposit</th><td>£{{ number_format($rental->deposit_amount ?? 0, 2) }}</td></tr>
                    <tr><th>Delivery</th><td>{{ $rental->offers_delivery ? 'Yes — £' . number_format($rental->delivery_fee ?? 0, 2) : 'No' }}</td></tr>
                    <tr><th>Location</th><td>{{ $rental->location ?? '—' }}</td></tr>
                    <tr><th>Views</th><td>{{ $rental->view_count }}</td></tr>
                    <tr><th>Featured</th><td>{{ $rental->is_featured ? 'Yes ⭐' : 'No' }}</td></tr>
                    <tr><th>Submitted</th><td>{{ $rental->created_at->format('d M Y') }}</td></tr>
                    @if($rental->approved_at)
                    <tr><th>Approved</th><td>{{ $rental->approved_at->format('d M Y') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Admin Actions --}}
        <div class="card">
            <div class="card-header">Admin Actions</div>
            <div class="card-body">
                <div class="actions-bar">
                    @if($rental->isPending())
                        <form action="/admin/rentals/{{ $rental->id }}/approve" method="POST">
                            @csrf
                            <button type="submit" class="btn-main btn-approve-main">
                                <i class="fa-solid fa-check-circle"></i> Approve Listing
                            </button>
                        </form>
                        <button onclick="openReject()" class="btn-main btn-reject-main">
                            <i class="fa-solid fa-times-circle"></i> Reject Listing
                        </button>
                    @endif
                    @if($rental->isApproved())
                        <form action="/admin/rentals/{{ $rental->id }}/toggle-featured" method="POST">
                            @csrf
                            <button type="submit" class="btn-main btn-feature-main">
                                <i class="fa-solid fa-star"></i>
                                {{ $rental->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </button>
                        </form>
                    @endif
                    <form action="/admin/rentals/{{ $rental->id }}" method="POST"
                          onsubmit="return confirm('Permanently delete this listing?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-main btn-delete-main">
                            <i class="fa-solid fa-trash"></i> Delete Listing
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Reject Modal --}}
<div class="reject-modal" id="rejectModal">
    <div class="reject-box">
        <h3><i class="fa-solid fa-times-circle me-2" style="color:#dc2626;"></i>Reject Rental Listing</h3>
        <form action="/admin/rentals/{{ $rental->id }}/reject" method="POST">
            @csrf
            <textarea name="rejection_reason" class="reject-ta" placeholder="Reason for rejection (min 10 characters)…" required minlength="10"></textarea>
            <div class="reject-actions">
                <button type="button" onclick="closeReject()" class="btn-cancel-small">Cancel</button>
                <button type="submit" class="btn-reject-small">Reject Listing</button>
            </div>
        </form>
    </div>
</div>
<script>
function openReject() { document.getElementById('rejectModal').classList.add('open'); }
function closeReject() { document.getElementById('rejectModal').classList.remove('open'); }
document.getElementById('rejectModal').addEventListener('click', function(e){ if(e.target===this) closeReject(); });
</script>

@endsection

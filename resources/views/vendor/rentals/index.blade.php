@extends('layouts.vendor')

@section('title', 'Rental Listings — Light As Air')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;margin:0;">Rental Equipment Listings</h1>
        <p style="color:#888;font-size:.9rem;margin:.2rem 0 0 0;">Manage your film lighting & grip gear available for rent</p>
    </div>
    <a href="{{ route('vendor.rentals.create') }}" class="btn-brand">
        <i class="bi bi-plus-lg"></i> New Rental Listing
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

{{-- Filter Toolbar --}}
<div class="content-card mb-4">
    <div class="content-card-body" style="padding:1rem 1.4rem;">
        <form method="GET" action="{{ route('vendor.rentals.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search equipment title..." value="{{ request('search') }}" style="border-radius:10px;font-size:.9rem;">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()" style="border-radius:10px;font-size:.9rem;">
                    <option value="">All Statuses</option>
                    @foreach(['pending' => 'Pending Approval', 'approved' => 'Approved / Active', 'rejected' => 'Rejected', 'draft' => 'Draft'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto ms-auto d-flex gap-2">
                <button type="submit" class="btn btn-dark" style="border-radius:10px;font-weight:700;font-size:.85rem;padding:.5rem 1.2rem;">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                <a href="{{ route('vendor.rentals.index') }}" class="btn btn-light" style="border-radius:10px;font-weight:600;font-size:.85rem;padding:.5rem 1rem;">Reset</a>
            </div>
        </form>
    </div>
</div>

@if($listings->isEmpty())
    <div class="content-card text-center p-5">
        <i class="bi bi-calendar3" style="font-size:3.5rem;color:#ccc;display:block;margin-bottom:1rem;"></i>
        <h3 style="font-weight:800;color:#333;margin-bottom:.5rem;">No Rental Listings Found</h3>
        <p style="color:#888;font-size:.9rem;max-width:450px;margin:0 auto 1.5rem;">List your film lighting, cameras, or grip gear for rental and start earning daily revenue.</p>
        <a href="{{ route('vendor.rentals.create') }}" class="btn-brand" style="display:inline-flex;">
            <i class="bi bi-plus-lg"></i> Create First Rental Listing
        </a>
    </div>
@else
    <div class="row g-4">
        @foreach($listings as $listing)
        <div class="col-lg-4 col-md-6">
            <div class="content-card h-100 d-flex flex-column" style="overflow:hidden;transition:transform .2s, box-shadow .2s;">
                <div style="position:relative;height:200px;background:#f5f4ef;overflow:hidden;">
                    <img src="{{ $listing->primaryImageUrl() }}" alt="{{ $listing->title }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\' fill=\'%23f5f4ef\'><text x=\'150\' y=\'105\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23888888\' text-anchor=\'middle\'>Equipment Image</text></svg>'">
                    <span class="badge bg-dark" style="position:absolute;top:.75rem;left:.75rem;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;padding:.4em .8em;border-radius:20px;">
                        <i class="bi bi-calendar3 me-1"></i> RENTAL
                    </span>
                    @if($listing->isApproved())
                        <span class="status-badge badge-approved" style="position:absolute;top:.75rem;right:.75rem;">Approved</span>
                    @elseif($listing->isPending())
                        <span class="status-badge badge-pending" style="position:absolute;top:.75rem;right:.75rem;">Pending Approval</span>
                    @elseif($listing->isRejected())
                        <span class="status-badge badge-rejected" style="position:absolute;top:.75rem;right:.75rem;">Rejected</span>
                    @else
                        <span class="status-badge" style="position:absolute;top:.75rem;right:.75rem;background:#eee;color:#555;">{{ ucfirst($listing->status) }}</span>
                    @endif
                </div>

                <div class="content-card-body d-flex flex-column flex-grow-1" style="padding:1.2rem;">
                    <div style="font-size:.75rem;font-weight:700;color:var(--brand-dark);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">
                        {{ $listing->category->name ?? 'Film Equipment' }}
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:800;color:#111;margin-bottom:.5rem;line-height:1.3;">
                        {{ Str::limit($listing->title, 50) }}
                    </h3>

                    <div style="font-size:.82rem;color:#666;display:flex;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <span><i class="bi bi-box-seam me-1"></i> Total: <strong>{{ $listing->total_qty }} unit(s)</strong></span>
                        @if($listing->brand)
                            <span><i class="bi bi-tag me-1"></i> {{ $listing->brand }}</span>
                        @endif
                    </div>

                    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:.75rem 1rem;margin-bottom:1rem;">
                        <div style="font-size:.72rem;font-weight:700;color:#8d6e63;text-transform:uppercase;">Daily Rental Rate</div>
                        <div style="font-size:1.4rem;font-weight:900;color:#111;">
                            £{{ number_format($listing->price_per_day, 2) }} <span style="font-size:.8rem;font-weight:600;color:#777;">/ day</span>
                        </div>
                        @if($listing->price_per_week > 0)
                            <div style="font-size:.78rem;color:#666;margin-top:.2rem;">
                                Weekly Rate: <strong>£{{ number_format($listing->price_per_week, 2) }} / wk</strong>
                            </div>
                        @endif
                        @if($listing->deposit_amount > 0)
                            <div style="font-size:.75rem;color:#888;margin-top:.2rem;">
                                <i class="bi bi-shield-lock me-1"></i> Security Deposit: £{{ number_format($listing->deposit_amount, 2) }}
                            </div>
                        @endif
                    </div>

                    @if($listing->isRejected() && $listing->rejection_reason)
                        <div class="alert alert-danger p-2 mb-3" style="font-size:.78rem;border-radius:8px;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Rejection Reason:</strong> {{ $listing->rejection_reason }}
                        </div>
                    @endif

                    <div class="mt-auto d-flex gap-2 pt-2" style="border-top:1px solid #f0efe9;">
                        <a href="{{ route('vendor.rentals.show', $listing->id) }}" class="btn btn-sm btn-light flex-grow-1" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                            <i class="bi bi-eye"></i> View
                        </a>
                        @if($listing->isApproved())
                            <a href="{{ route('vendor.rentals.calendar', $listing->id) }}" class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                                <i class="bi bi-calendar-event"></i> Calendar
                            </a>
                        @endif
                        <a href="{{ route('vendor.rentals.edit', $listing->id) }}" class="btn btn-sm btn-outline-brand" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('vendor.rentals.destroy', $listing->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this rental listing?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $listings->appends(request()->query())->links() }}
    </div>
@endif
@endsection

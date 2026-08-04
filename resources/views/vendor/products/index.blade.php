@extends('layouts.vendor')

@section('title', 'Sell Listings — Light As Air')

@section('content')
<div class="page-header mb-4" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 class="page-title" style="font-size:1.6rem;font-weight:800;margin:0;">Sell Listings</h1>
        <p style="color:#888;font-size:.9rem;margin:.2rem 0 0 0;">Manage your items for sale on the Light As Air marketplace</p>
    </div>
    <a href="{{ route('vendor.products.create') }}" class="btn-brand">
        <i class="bi bi-plus-lg me-1"></i> New Sell Listing
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius:12px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4" style="border-radius:12px;">{{ session('error') }}</div>
@endif

{{-- Filter Bar --}}
<div class="content-card mb-4">
    <div class="content-card-body" style="padding:1rem 1.4rem;">
        <form method="GET" action="{{ route('vendor.products.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search listing title..." value="{{ request('search') }}" style="border-radius:10px;font-size:.9rem;">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()" style="border-radius:10px;font-size:.9rem;">
                    <option value="">All Statuses</option>
                    @foreach(['pending' => 'Pending Approval', 'approved' => 'Approved', 'rejected' => 'Rejected', 'sold' => 'Sold', 'draft' => 'Draft'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto ms-auto d-flex gap-2">
                <button type="submit" class="btn btn-dark" style="border-radius:10px;font-weight:700;font-size:.85rem;padding:.5rem 1.2rem;">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-light" style="border-radius:10px;font-weight:600;font-size:.85rem;padding:.5rem 1rem;">Reset</a>
            </div>
        </form>
    </div>
</div>

@if($products->isEmpty())
    <div class="content-card text-center p-5">
        <i class="bi bi-box-seam" style="font-size:3.5rem;color:#ccc;display:block;margin-bottom:1rem;"></i>
        <h3 style="font-weight:800;color:#333;margin-bottom:.5rem;">No Sell Listings Found</h3>
        <p style="color:#888;font-size:.9rem;max-width:450px;margin:0 auto 1.5rem;">List your film equipment, cameras, lenses, or lighting gear for sale.</p>
        <a href="{{ route('vendor.products.create') }}" class="btn-brand" style="display:inline-flex;">
            <i class="bi bi-plus-lg me-1"></i> Create First Listing
        </a>
    </div>
@else
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6">
            <div class="content-card h-100 d-flex flex-column" style="overflow:hidden;transition:transform .2s, box-shadow .2s;">
                <div style="position:relative;height:200px;background:#f5f4ef;overflow:hidden;">
                    <img src="{{ $product->primaryImageUrl() }}" alt="{{ $product->title }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\' fill=\'%23f5f4ef\'><text x=\'150\' y=\'105\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23888888\' text-anchor=\'middle\'>Product Image</text></svg>'">
                    <span class="badge bg-dark" style="position:absolute;top:.75rem;left:.75rem;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;padding:.4em .8em;border-radius:20px;">
                        <i class="bi bi-shop me-1"></i> FOR SALE
                    </span>
                    @if($product->isApproved())
                        <span class="status-badge badge-approved" style="position:absolute;top:.75rem;right:.75rem;">Approved</span>
                    @elseif($product->isPending())
                        <span class="status-badge badge-pending" style="position:absolute;top:.75rem;right:.75rem;">Pending Approval</span>
                    @elseif($product->isRejected())
                        <span class="status-badge badge-rejected" style="position:absolute;top:.75rem;right:.75rem;">Rejected</span>
                    @elseif($product->isSold())
                        <span class="status-badge" style="position:absolute;top:.75rem;right:.75rem;background:#7c3aed;color:#fff;">Sold</span>
                    @else
                        <span class="status-badge" style="position:absolute;top:.75rem;right:.75rem;background:#eee;color:#555;">{{ ucfirst($product->status) }}</span>
                    @endif
                </div>

                <div class="content-card-body d-flex flex-column flex-grow-1" style="padding:1.2rem;">
                    <div style="font-size:.75rem;font-weight:700;color:var(--brand-dark);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">
                        {{ $product->category->name ?? 'Film Equipment' }}
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:800;color:#111;margin-bottom:.5rem;line-height:1.3;">
                        {{ Str::limit($product->title, 50) }}
                    </h3>

                    <div style="font-size:.82rem;color:#666;display:flex;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <span><i class="bi bi-box-seam me-1"></i> Stock: <strong>{{ $product->quantity }} unit(s)</strong></span>
                        <span><i class="bi bi-info-circle me-1"></i> {{ ucfirst($product->condition) }}</span>
                    </div>

                    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:.75rem 1rem;margin-bottom:1rem;">
                        <div style="font-size:.72rem;font-weight:700;color:#8d6e63;text-transform:uppercase;">Sale Price</div>
                        <div style="font-size:1.4rem;font-weight:900;color:#111;">
                            £{{ number_format($product->price, 2) }}
                        </div>
                    </div>

                    @if($product->isRejected() && $product->rejection_reason)
                        <div class="alert alert-danger p-2 mb-3" style="font-size:.78rem;border-radius:8px;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Rejection Reason:</strong> {{ $product->rejection_reason }}
                        </div>
                    @endif

                    <div class="mt-auto d-flex gap-2 pt-2" style="border-top:1px solid #f0efe9;">
                        <a href="{{ route('vendor.products.show', $product->id) }}" class="btn btn-sm btn-light flex-grow-1" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                            <i class="bi bi-eye"></i> View
                        </a>
                        @unless($product->isApproved())
                            <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-outline-brand" style="border-radius:8px;font-weight:700;font-size:.8rem;">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        @endunless
                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?')" style="display:inline;">
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
        {{ $products->appends(request()->query())->links() }}
    </div>
@endif
@endsection

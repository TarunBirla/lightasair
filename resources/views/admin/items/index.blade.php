@extends('layouts.admin')

@section('page-title', 'Items')
@section('breadcrumb', 'Admin / Items')

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

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFC700;
        color: #111;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background .2s, transform .15s;
         font-family: 'Akshar', sans-serif;
    }

    .btn-add:hover {
        background: #E6B200;
        color: #111;
        transform: translateY(-1px);
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
        padding: 13px 14px;
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
        padding: 13px 14px;
        font-size: 14px;
        color: #111;
        vertical-align: middle;
    }

    .item-img {
        width: 60px; height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #E8E6DF;
    }

    .item-title {
        font-weight: 600;
        color: #111;
    }

    .item-title small {
        display: block;
        font-size: 12px;
        color: #888;
        font-weight: 400;
    }

    .qty-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #EAF3FF;
        color: #1a5fb4;
    }

    .avail-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #EDFAF0;
        color: #1a7a3a;
    }

    .price-tag {
        font-weight: 700;
        color: #111;
        font-size: 15px;
    }

    .price-tag span {
        font-size: 11px;
        color: #888;
        font-weight: 400;
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
    .badge-inactive { background: #F5F5F5; color: #888; }

    .action-group {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        background: #FFF3B0;
        color: #B38A00;
        border: none;
        cursor: pointer;
         font-family: 'Akshar', sans-serif;
        transition: background .2s;
    }

    .btn-edit:hover { background: #FFC700; color: #111; }

    .btn-del {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: #FEF0F0;
        color: #c0392b;
        border: none;
        cursor: pointer;
         font-family: 'Akshar', sans-serif;
        transition: background .2s;
    }

    .btn-del:hover { background: #c0392b; color: #fff; }

    .id-badge {
        display: inline-block;
        background: #F7F6F1;
        color: #888;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .cat-tag {
        display: inline-block;
        background: #FFF3B0;
        color: #B38A00;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-row td {
        text-align: center;
        padding: 60px 0;
        color: #bbb;
        font-size: 14px;
    }
    .custom-pagination{
    display:flex;
    justify-content:center;
    margin-top:25px;
}

.custom-pagination nav{
    width:auto;
}

.custom-pagination .pagination{
    display:flex;
    align-items:center;
    gap:8px;
    margin:0;
    padding:0;
    list-style:none;
}

.custom-pagination .page-item{
    list-style:none;
}

.custom-pagination .page-link{
    min-width:42px;
    height:42px;
    padding:0 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:none;
    border-radius:12px;
    background:#fff;
    color:#374151;
    font-weight:600;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    text-decoration:none;
    transition:.25s;
}

.custom-pagination .page-link:hover{
    background:#FFC700;
    color:#111;
    transform:translateY(-2px);
}

.custom-pagination .active .page-link{
    background:#FFC700;
    color:#111;
    font-weight:700;
}

.custom-pagination .disabled .page-link{
    opacity:.4;
    cursor:not-allowed;
}

.custom-pagination svg{
    width:16px;
    height:16px;
}
    
</style>

<div class="page-header">
    <h3><i class="fa-solid fa-box-open me-2" style="color:#FFC700"></i>Items</h3>
    <a href="{{ route('items.create') }}" class="btn-add">
        <i class="fa-solid fa-plus"></i> Add Item
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Available</th>
                <th>Price/Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td><span class="id-badge">{{ $item->id }}</span></td>
                <td>
                    <img src="{{ asset('uploads/items/'.$item->image) }}" class="item-img" alt="{{ $item->title }}">
                </td>
                <td>
                    <span class="item-title">
                        {{ $item->title }}
                    </span>
                </td>
                <td>
                    <span class="cat-tag">{{ $item->category->name ?? '—' }}</span>
                </td>
                <td>
                    <span class="qty-pill">{{ $item->qty }}</span>
                </td>
                <td>
                    <span class="avail-pill">{{ $item->available_qty }}</span>
                </td>
                <td>
                    <span class="price-tag">£{{ $item->price_per_day }}<span>/day</span></span>
                </td>
                <td>
                    @if($item->status == 'active')
                        <span class="badge-status badge-active">
                            <i class="fa-solid fa-circle" style="font-size:7px"></i> Active
                        </span>
                    @else
                        <span class="badge-status badge-inactive">
                            <i class="fa-regular fa-circle" style="font-size:7px"></i> Inactive
                        </span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('items.edit', $item->id) }}" class="btn-edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-del" onclick="return confirm('Delete this item?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">
                    <i class="fa-solid fa-box-open" style="font-size:36px;display:block;margin-bottom:10px"></i>
                    No items yet. <a href="{{ route('items.create') }}" style="color:#FFC700;font-weight:600">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
   <div class="custom-pagination">
    {{ $items->onEachSide(1)->links() }}
</div>
</div>

@endsection
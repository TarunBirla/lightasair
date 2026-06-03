@extends('layouts.admin')

@section('page-title', 'Categories')
@section('breadcrumb', 'Admin / Categories')

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
            padding: 13px 18px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #888;
            text-align: left;
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

        .cat-img-wrap {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E8E6DF;
            background: #F7F6F1;
        }

        .cat-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .badge-active {
            background: #EDFAF0;
            color: #1a7a3a;
        }

        .badge-inactive {
            background: #F5F5F5;
            color: #888;
        }

        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            background: #FFF3B0;
            color: #B38A00;
            border: none;
            cursor: pointer;
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-edit:hover {
            background: #FFC700;
            color: #111;
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
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-del:hover {
            background: #c0392b;
            color: #fff;
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

        .cat-name {
            font-weight: 600;
            color: #111;
        }

        .empty-row td {
            text-align: center;
            padding: 60px 0;
            color: #bbb;
            font-size: 14px;
        }
    </style>

    <div class="page-header">
        <h3><i class="fa-solid fa-layer-group me-2" style="color:#FFC700"></i>Categories</h3>
        <a href="{{ route('category.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i> Add Category
        </a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><span class="id-badge">{{ $category->id }}</span></td>
                        <!-- <td>
                            <div class="cat-img-wrap">
                                <img src="{{ asset('uploads/category/'.$category->image) }}" alt="{{ $category->name }}">
                            </div>
                        </td> -->
                        <td>

    @if($category->images->count())

        <div style="position:relative;width:56px;">

            <div class="cat-img-wrap">
                <img
                    src="{{ asset('uploads/category/'.$category->images->first()->image) }}"
                    alt="">
            </div>

            @if($category->images->count() > 1)

                <span style="
                    position:absolute;
                    top:-8px;
                    right:-8px;
                    background:#FFC700;
                    color:#000;
                    border-radius:50%;
                    width:22px;
                    height:22px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:11px;
                    font-weight:700;
                ">
                    +{{ $category->images->count() - 1 }}
                </span>

            @endif

        </div>

    @endif

</td>
                        <td><span class="cat-name">{{ $category->name }}</span></td>
                        <td>
                            @if($category->status == 'active')
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
                                <a href="{{ route('category.edit', $category->id) }}" class="btn-edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del" onclick="return confirm('Delete this category?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">
                            <i class="fa-solid fa-layer-group" style="font-size:36px;display:block;margin-bottom:10px"></i>
                            No categories yet. <a href="{{ route('category.create') }}"
                                style="color:#FFC700;font-weight:600">Add one?</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
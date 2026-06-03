@extends('layouts.admin')

@section('page-title','Television')
@section('breadcrumb','Admin / Television')

@section('content')

<style>
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.section-header h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}
.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    background: #FFC700;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-add:hover { background: #FFC700; color: #fff; }
.btn-add svg { width: 16px; height: 16px; }

.data-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.data-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}
.data-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.data-table tbody tr:last-child { border-bottom: none; }
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody td {
    padding: 12px 14px;
    color: #334155;
    font-size: 13.5px;
    vertical-align: middle;
    overflow: hidden;
}
.td-id {
    color: #94a3b8;
    font-size: 13px;
    font-weight: 500;
}
.tv-img {
    width: 72px;
    height: 46px;
    object-fit: contain;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 3px;
    display: block;
}
.tv-title {
    font-weight: 500;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tag-badge {
    display: inline-block;
    padding: 3px 10px;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.tv-desc {
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.actions-cell {
    display: flex;
    gap: 6px;
    align-items: center;
}
.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s;
    white-space: nowrap;
}
.btn-edit:hover { background: #dbeafe; color: #1d4ed8; }
.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.btn-delete:hover { background: #ffe4e6; }
.btn-edit svg, .btn-delete svg { width: 13px; height: 13px; flex-shrink: 0; }

.table-footer {
    padding: 14px 16px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    justify-content: flex-end;
}
</style>

<div class="section-header">
    <h4>Television List</h4>
    <a href="{{ route('televisions.create') }}" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Television
    </a>
</div>

<div class="data-card">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:50px">ID</th>
                <th style="width:90px">Image</th>
                <th style="width:140px">Title</th>
                <th style="width:90px">Tag</th>
                <th>Description</th>
                <th style="width:160px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($televisions as $tv)
            <tr>
                <td class="td-id">#{{ $tv->id }}</td>
                <td>
                    <img src="{{ asset('uploads/televisions/'.$tv->image) }}"
                         alt="{{ $tv->title }}"
                         class="tv-img">
                </td>
                <td>
                    <span class="tv-title">{{ $tv->title }}</span>
                </td>
                <td>
                    <span class="tag-badge">{{ $tv->tag }}</span>
                </td>
                <td>
                    <p class="tv-desc">{{ $tv->description }}</p>
                </td>
                <td>
                    <div class="actions-cell">
                        <a href="{{ route('televisions.edit', $tv->id) }}" class="btn-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('televisions.destroy', $tv->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this television?')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 2.5rem; color:#94a3b8; font-size:14px;">
                    No televisions found. <a href="{{ route('televisions.create') }}" style="color:#2563eb">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-footer">
        {{ $televisions->links() }}
    </div>
</div>

@endsection
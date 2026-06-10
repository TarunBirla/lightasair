@extends('layouts.admin')

@section('page-title','Portfolio')
@section('breadcrumb','Admin / Portfolio')

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
}
.data-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 12px 16px;
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
    padding: 14px 16px;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}
.td-id {
    color: #94a3b8;
    font-size: 13px;
    font-weight: 500;
}
.portfolio-img {
    width: 100px;
    height: 65px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    display: block;
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
}
.btn-delete:hover { background: #ffe4e6; }
.btn-edit svg, .btn-delete svg { width: 13px; height: 13px; }

.table-footer {
    padding: 14px 16px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    justify-content: flex-end;
}

.img-tablepagination{
    display:flex;
    justify-content:center;
    margin-top:25px;
}

.img-tablepagination nav{
    width:auto;
}

.img-tablepagination .pagination{
    display:flex;
    align-items:center;
    gap:8px;
    margin:0;
    padding:0;
    list-style:none;
}

.img-tablepagination .page-item{
    list-style:none;
}

.img-tablepagination .page-link{
    min-width:42px;
    height:42px;
    padding:0 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid #E8E6DF;
    background:#fff;
    color:#111;
    font-size:14px;
    font-weight:600;
    border-radius:10px;
    text-decoration:none;
    transition:all .2s ease;
}

.img-tablepagination .page-link:hover{
    background:#FFC700;
    border-color:#FFC700;
    color:#111;
}

.img-tablepagination .page-item.active .page-link{
    background:#FFC700;
    border-color:#FFC700;
    color:#111;
}

.img-tablepagination .page-item.disabled .page-link{
    opacity:.5;
    cursor:not-allowed;
}

.img-tablepagination svg{
    width:16px;
    height:16px;
}

.img-tablepagination p{
    display:none;
}
</style>

<div class="section-header">
    <h4>Portfolio List</h4>
    <a href="{{ route('portfolios.create') }}" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Portfolio
    </a>
</div>

<div class="data-card">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:60px">ID</th>
                <th style="width:130px">Image</th>
                <th style="width:180px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($portfolios as $portfolio)
            <tr>
                <td class="td-id">#{{ $portfolio->id }}</td>
                <td>
                    <img src="{{ asset('uploads/portfolios/'.$portfolio->image) }}"
                         alt="Portfolio #{{ $portfolio->id }}"
                         class="portfolio-img">
                </td>
                <td>
                    <div class="actions-cell">
                        <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="btn-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('portfolios.destroy', $portfolio->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this portfolio item?')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center; padding: 2.5rem; color:#94a3b8; font-size:14px;">
                    No portfolio items found. <a href="{{ route('portfolios.create') }}" style="color:#2563eb">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="img-tablepagination">
        {{ $portfolios->links() }}
    </div>
</div>

@endsection
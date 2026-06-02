@extends('layouts.admin')

@section('page-title', 'Requests')
@section('breadcrumb', 'Admin / Requests')

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

        .thumb-img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #E8E6DF;
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

        .empty-row td {
            text-align: center;
            padding: 60px 0;
            color: #bbb;
            font-size: 14px;
        }
        .custom-pagination {
    display: flex;
    justify-content: center;
    margin: 25px 0;
}

.custom-pagination nav {
    display: flex;
}

.custom-pagination .pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.custom-pagination .page-item .page-link {
    min-width: 42px;
    height: 42px;
    border-radius: 10px;
    border: 1px solid #E8E6DF;
    background: #fff;
    color: #111;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all .2s ease;
}

.custom-pagination .page-item .page-link:hover {
    background: #FFC700;
    border-color: #FFC700;
    color: #111;
}

.custom-pagination .page-item.active .page-link {
    background: #FFC700;
    border-color: #FFC700;
    color: #111;
}

.custom-pagination .page-item.disabled .page-link {
    background: #f5f5f5;
    color: #aaa;
    cursor: not-allowed;
}

.custom-pagination svg {
    width: 16px;
    height: 16px;
}
    </style>



    <div class="table-card">
        <table>
            <thead>
                <tr>

                    <th>Title</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)

                    <tr>
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->phone }}</td>
                        <td>{{ $request->message }}</td>
                         <td>

        <form
            action="{{ route('admin.requests.delete',$request->id) }}"
            method="POST"
            onsubmit="return confirm('Delete this request?')"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn-del"
            >
                <i class="fa-solid fa-trash"></i>
                Delete
            </button>

        </form>

    </td>
                    </tr>

                @empty

                    <tr class="empty-row">
                        <td colspan="5">
                            No Request Found
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
      <div class="custom-pagination">
    {{ $requests->onEachSide(1)->links() }}
</div>
    </div>

@endsection
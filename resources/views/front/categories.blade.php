@extends('front.layouts.app')

@section('content')

<style>
    /* ── CATEGORIES ── */
        .cat-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .cat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--brand);
        }
        .cat-card-body {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cat-card-body h6 {
            font-weight: 700;
            font-size: .93rem;
            color: var(--dark);
            margin: 0;
        }

        .cat-arrow {
            width: 30px;
            height: 30px;
            background: var(--brand-lt);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-size: .75rem;
            transition: background .2s;
        }

        .cat-card:hover .cat-arrow {
            background: var(--brand);
        }
        .pagination {
justify-content: center;
gap: 8px;
margin-top: 40px;
}

.pagination .page-item .page-link {
border: none;
min-width: 45px;
height: 45px;
border-radius: 12px;
background: #fff;
color: #111;
font-weight: 700;
display: flex;
align-items: center;
justify-content: center;
box-shadow: 0 4px 12px rgba(0,0,0,.08);
transition: all .3s ease;
}

.pagination .page-item .page-link:hover {
background: #ffc700;
color: #111;
transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
background: #ffc700;
color: #111;
border-color: #ffc700;
box-shadow: 0 8px 20px rgba(255,199,0,.35);
}

.pagination .page-item.disabled .page-link {
opacity: .5;
cursor: not-allowed;
}

@media(max-width:576px){

.pagination .page-item .page-link{
    min-width:40px;
    height:40px;
    font-size:14px;
}

}

</style>


    <div class="container py-5">

        <h2 class="mb-4">
            All Categories
        </h2>

        <div class="row g-4">

            @foreach($categories as $category)

               <div class="col-6 col-md-3">
                        <a href="{{ url('/category/' . $category->id) }}" class="text-decoration-none text-dark">

                            <div class="cat-card">
                                <!-- <div style="overflow:hidden;">
                                        <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->name }}">
                                    </div> -->
                                <div class="cat-card-body">
                                    <h6>{{ $category->name }}</h6>
                                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </div>
                        </a>
                    </div>

            @endforeach

        </div>

        <div class="mt-4">

            <!-- {{ $categories->links() }} -->
            <div class="d-flex justify-content-center mt-5">
    {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

        </div>

    </div>

@endsection
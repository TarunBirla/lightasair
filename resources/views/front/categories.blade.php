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

            {{ $categories->links() }}

        </div>

    </div>

@endsection
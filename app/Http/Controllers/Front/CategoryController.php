<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where(
            'status',
            'active'
        )
        ->orderBy('number', 'asc')
        ->paginate(12);

        return view(
            'front.categories',
            compact('categories')
        );
    }

public function show($id)
{
    $category = Category::with('images')
        ->findOrFail($id);

    $categories = Category::where(
        'status',
        'active'
    )
     ->orderBy('number', 'asc')
    ->get();

    $items = Item::where(
        'category_id',
        $id
    )
    ->where(
        'status',
        'active'
    )
    ->paginate(12);

    return view(
        'front.category-items',
        compact(
            'category',
            'items',
            'categories'
        )
    );
}
}
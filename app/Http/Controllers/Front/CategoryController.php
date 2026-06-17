<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('number', 'asc')
            ->paginate(12);

        return view(
            'front.categories',
            compact('categories')
        );
    }


   public function show(Request $request, $id)
{
    $category = Category::with('images')
        ->findOrFail($id);

    $categories = Category::where('status', 'active')
        ->orderBy('number', 'asc')
        ->get();

    $items = Item::where('category_id', $id)
        ->where('status', 'active');

    // Search by title
    if ($request->filled('search')) {
        $items->where('title', 'LIKE', '%' . $request->search . '%');
    }

    $items = $items
        ->orderBy('sort_order', 'asc')
        ->paginate(12);

    return view(
        'front.category-items',
        compact('category', 'items', 'categories')
    );
}
}
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
        $category = Category::with('images')->findOrFail($id);

        $categories = Category::where('status', 'active')
            ->orderBy('number', 'asc')
            ->get();

        $query = Item::where('category_id', $id)
            ->where('status', 'active');

        $type = $request->query('type', 'all');
        if ($type === 'sell') {
            $query->where(function($q) {
                $q->where('is_sell', 1)->orWhereNull('is_sell');
            });
        } elseif ($type === 'rental') {
            $query->where('is_rental', 1);
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $items = $query->orderBy('sort_order', 'asc')->paginate(12);

        return view(
            'front.category-items',
            compact('category', 'items', 'categories', 'type')
        );
    }
}
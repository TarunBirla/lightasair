<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 'active')
            ->orderBy('number', 'asc')
            ->get();

        // $items = Item::where('status', 'active');
        $items = Item::orderBy('category_id')
            ->orderBy('sort_order','asc')
            ->get();

        // Category Filter
        if ($request->filled('category')) {
            $items->where('category_id', $request->category);
        }

        $items = $items->latest()->paginate(12);

        return view(
            'front.items',
            compact('items', 'categories')
        );
    }
}
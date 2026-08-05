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

        $query = Item::where('status', 'active');

        $type = $request->query('type', 'all');
        if ($type === 'sell') {
            $query->where(function($q) {
                $q->where('is_sell', 1)->orWhereNull('is_sell');
            });
        } elseif ($type === 'rental') {
            $query->where('is_rental', 1);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $items = $query->orderBy('category_id', 'asc')
            ->orderBy('sort_order', 'asc')
            ->paginate(12);

        return view(
            'front.items',
            compact('items', 'categories', 'type')
        );
    }
}
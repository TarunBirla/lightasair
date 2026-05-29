<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where(
            'status',
            'active'
        )
        ->latest()
        ->paginate(12);

        return view(
            'front.items',
            compact('items')
        );
    }
}
<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;

class HomeController extends Controller
{
   public function index()
{
    $banners = Banner::where(
        'status',
        'active'
    )->get();

    $categories = Category::where(
        'status',
        'active'
    )
    ->latest()
    ->take(8)
    ->get();

    $items = Item::where(
        'status',
        'active'
    )
    ->latest()
    ->take(8)
    ->get();

    return view(
        'front.home',
        compact(
            'banners',
            'categories',
            'items'
        )
    );
}

    public function itemDetail($id)
{
    $item = Item::findOrFail($id);

    return view(
        'front.item-detail',
        compact('item')
    );
}
}
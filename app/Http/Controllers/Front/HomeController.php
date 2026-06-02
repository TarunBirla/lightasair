<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\RequestLead;
use Illuminate\Http\Request;


class HomeController extends Controller
{


public function guestRequest(Request $request)
{
    $request->validate([
        'item_id' => 'required',
        'name'    => 'required',
        'email'   => 'required|email',
        'phone'   => 'required'
    ]);

    $item = Item::findOrFail($request->item_id);

    $lead = RequestLead::create([
        'item_id'   => $item->id,
        'item_name' => $item->title,
        'name'      => $request->name,
        'email'     => $request->email,
        'phone'     => $request->phone,
        'message'   => $request->message
    ]);

    return response()->json([
        'status' => true,
        'item_name' => $item->title,
        'name' => $lead->name,
        'email' => $lead->email,
        'phone' => $lead->phone
    ]);
}
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
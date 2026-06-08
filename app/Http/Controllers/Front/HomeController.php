<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\RequestLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestLeadMail;


class HomeController extends Controller
{


public function guestRequest(Request $request)
{
    $request->validate([
        'items' => 'required|array|min:1',
        'name'  => 'required',
        'email' => 'required|email',
        'phone' => 'required'
    ]);

    $items = [];
    $itemsText = '';

    foreach ($request->items as $item)
    {
        RequestLead::create([
            'item_id'   => $item['id'],
            'item_name' => $item['title'],
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'message'   => $request->message
        ]);

        $items[] = $item['title'];

        $itemsText .= "• ".$item['title']."\n";
    }

    $mailData = [
        'items'   => $items,
        'name'    => $request->name,
        'email'   => $request->email,
        'phone'   => $request->phone,
        'message' => $request->message,
    ];

    try {
        Mail::to('tbirla120@gmail.com')
            ->send(new RequestLeadMail($mailData));
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }

    return response()->json([
        'status' => true,
        'items'  => $itemsText, // WhatsApp message ke liye
        'name'   => $request->name,
        'email'  => $request->email,
        'phone'  => $request->phone,
        'message'=> $request->message
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
    ->orderBy('number', 'asc')
    
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
<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\GeneratorBanner;
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
        $defaultType = $request->input('product_type', 'sell');

        foreach ($request->items as $item)
        {
            $itemType = strtolower($item['type'] ?? $defaultType);
            if ($itemType !== 'rental') {
                $itemType = 'sell';
            }

            RequestLead::create([
                'item_id'      => $item['id'] ?? null,
                'item_name'    => $item['title'] ?? 'Product',
                'name'         => $request->name,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'message'      => $request->message,
                'product_type' => $itemType,
            ]);

            $typeLabel = ($itemType === 'rental') ? 'Rental Request' : 'Selling Request';
            $items[]   = ($item['title'] ?? 'Product') . " [" . $typeLabel . "]";
            $itemsText .= "• " . ($item['title'] ?? 'Product') . " (" . $typeLabel . ")\n";
        }

        $mailData = [
            'items'        => $items,
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'message'      => $request->message,
            'product_type' => $defaultType,
        ];

        try {
            Mail::to('tbirla120@gmail.com')
                ->send(new RequestLeadMail($mailData));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return response()->json([
            'status'       => true,
            'items'        => $itemsText,
            'product_type' => $defaultType,
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'message'      => $request->message
        ]);
    }

    public function index()
    {
        $banners = Banner::where('status','active')->get();
        $generatorbanners = GeneratorBanner::where('status',1)->get();
        $categories = Category::where('status','active')->orderBy('number','asc')->take(8)->get();
        $items = Item::where('status','active')->orderBy('category_id','asc')->orderBy('sort_order','asc')->take(8)->get();

        return view('front.home', compact('banners', 'categories', 'items', 'generatorbanners'));
    }

    public function itemDetail($id)
    {
        $item = Item::findOrFail($id);
        return view('front.item-detail', compact('item'));
    }
}
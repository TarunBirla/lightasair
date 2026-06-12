<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratorBanner;
use App\Models\Item;
use Illuminate\Http\Request;

class GeneratorBannerController extends Controller
{
    public function index()
    {
        $banners = GeneratorBanner::latest()->get();

        return view(
            'admin.generator-banners.index',
            compact('banners')
        );
    }

    public function create()
    {
        $items = Item::all();

        return view(
            'admin.generator-banners.create',
            compact('items')
        );
    }

    public function store(Request $request)
    {
        $image = '';

        if ($request->hasFile('image')) {

            $image = time().'_'.$request->image->getClientOriginalName();

            $request->image->move(
                public_path('uploads/generator-banner'),
                $image
            );
        }

        GeneratorBanner::create([
            'title' => $request->title,
            'image' => $image,
            'link' => $request->link,
            'item_id' => $request->item_id,
            'status' => $request->status
        ]);

        return redirect()
            ->route('generator-banners.index');
    }

    public function edit($id)
    {
        $banner = GeneratorBanner::findOrFail($id);

        $items = Item::all();

        return view(
            'admin.generator-banners.edit',
            compact('banner','items')
        );
    }

    public function update(Request $request,$id)
    {
        $banner = GeneratorBanner::findOrFail($id);

        if($request->hasFile('image'))
        {
            $image = time().'_'.$request->image->getClientOriginalName();

            $request->image->move(
                public_path('uploads/generator-banner'),
                $image
            );

            $banner->image = $image;
        }

        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->item_id = $request->item_id;
        $banner->status = $request->status;

        $banner->save();

        return redirect()
            ->route('generator-banners.index');
    }

    public function destroy($id)
    {
        GeneratorBanner::destroy($id);

        return back();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $image = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/banner'), $image);
        }

        Banner::create([
            'title' => $request->title,
            'image' => $image,
            'status' => $request->status
        ]);

        return redirect()->route('banner.index')
            ->with('success', 'Banner Added Successfully');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $image = $banner->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/banner'), $image);
        }

        $banner->update([
            'title' => $request->title,
            'image' => $image,
            'status' => $request->status
        ]);

        return redirect()->route('banner.index')
            ->with('success', 'Banner Updated Successfully');
    }

    public function destroy($id)
    {
        Banner::destroy($id);

        return redirect()->back()
            ->with('success', 'Banner Deleted');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);

        return view(
            'admin.brands.index',
            compact('brands')
        );
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'image'=>'required|image'
        ]);

        $image = time().'.'.$request->image->extension();

        $request->image->move(
            public_path('uploads/brands'),
            $image
        );

        Brand::create([
            'title'=>$request->title,
            'image'=>$image
        ]);

        return redirect()
            ->route('brands.index')
            ->with('success','Brand Added');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }

    public function update(Request $request,$id)
    {
        $brand = Brand::findOrFail($id);

        $image = $brand->image;

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/brands'),
                $image
            );
        }

        $brand->update([
            'title'=>$request->title,
            'image'=>$image
        ]);

        return redirect()
            ->route('brands.index')
            ->with('success','Brand Updated');
    }

    public function destroy($id)
    {
        Brand::destroy($id);

        return back()
            ->with('success','Brand Deleted');
    }
}
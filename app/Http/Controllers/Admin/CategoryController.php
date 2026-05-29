<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view(
            'admin.category.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);

        $image = null;

        if($request->hasFile('image'))
        {
            $file = $request->file('image');

            $image = time().'.'.$file->extension();

            $file->move(
                public_path('uploads/category'),
                $image
            );
        }

        Category::create([
            'name'=>$request->name,
            'image'=>$image,
            'status'=>$request->status
        ]);

        return redirect()
            ->route('category.index')
            ->with(
                'success',
                'Category Added Successfully'
            );
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view(
            'admin.category.edit',
            compact('category')
        );
    }

    public function update(Request $request,$id)
    {
        $category = Category::findOrFail($id);

        $image = $category->image;

        if($request->hasFile('image'))
        {
            $file = $request->file('image');

            $image = time().'.'.$file->extension();

            $file->move(
                public_path('uploads/category'),
                $image
            );
        }

        $category->update([
            'name'=>$request->name,
            'image'=>$image,
            'status'=>$request->status
        ]);

        return redirect()
            ->route('category.index')
            ->with(
                'success',
                'Category Updated Successfully'
            );
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return redirect()
            ->back()
            ->with(
                'success',
                'Category Deleted Successfully'
            );
    }
}
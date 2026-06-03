<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
   use App\Models\CategoryImage;


class CategoryController extends Controller
{
    public function index()
{
   $categories = Category::with('images')->latest()->get();

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
    $category = Category::create([
        'name' => $request->name,
        'status' => $request->status
    ]);

    if($request->hasFile('images'))
    {
        foreach($request->file('images') as $file)
        {
            $image = time().rand(111,999).'.'.$file->extension();

            $file->move(
                public_path('uploads/category'),
                $image
            );

            CategoryImage::create([
                'category_id' => $category->id,
                'image' => $image
            ]);
        }
    }

    return redirect()->route('category.index');
}

   public function edit($id)
{
    $category = Category::with('images')->findOrFail($id);

    return view(
        'admin.category.edit',
        compact('category')
    );
}

   public function update(Request $request,$id)
{
    $category = Category::findOrFail($id);

    $category->update([
        'name'   => $request->name,
        'status' => $request->status
    ]);

    if($request->hasFile('images'))
    {
        foreach($request->file('images') as $file)
        {
            $image = time().rand(111,999).'.'.$file->extension();

            $file->move(
                public_path('uploads/category'),
                $image
            );

            CategoryImage::create([
                'category_id' => $category->id,
                'image'       => $image
            ]);
        }
    }

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
    public function deleteImage($id)
{
    CategoryImage::findOrFail($id)->delete();

    return back();
}
}
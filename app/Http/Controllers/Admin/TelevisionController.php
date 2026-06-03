<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Television;
use Illuminate\Http\Request;

class TelevisionController extends Controller
{
    public function index()
    {
        $televisions = Television::latest()->paginate(10);

        return view(
            'admin.televisions.index',
            compact('televisions')
        );
    }

    public function create()
    {
        return view('admin.televisions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'tag' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        $image = time().'.'.$request->image->extension();

        $request->image->move(
            public_path('uploads/televisions'),
            $image
        );

        Television::create([
            'title' => $request->title,
            'tag' => $request->tag,
            'description' => $request->description,
            'image' => $image
        ]);

        return redirect()
            ->route('televisions.index')
            ->with('success','Television Added');
    }

    public function edit($id)
    {
        $television = Television::findOrFail($id);

        return view(
            'admin.televisions.edit',
            compact('television')
        );
    }

    public function update(Request $request,$id)
    {
        $television = Television::findOrFail($id);

        $image = $television->image;

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/televisions'),
                $image
            );
        }

        $television->update([
            'title' => $request->title,
            'tag' => $request->tag,
            'description' => $request->description,
            'image' => $image
        ]);

        return redirect()
            ->route('televisions.index')
            ->with('success','Television Updated');
    }

    public function destroy($id)
    {
        Television::destroy($id);

        return back()
            ->with('success','Television Deleted');
    }
}
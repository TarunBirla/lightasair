<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $items = Item::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(20);

        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where(
            'status',
            'active'
        )->get();

        return view(
            'admin.items.create',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'qty' => 'required',
            'price_per_day' => 'required'
        ]);

        $images = [];

        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $file) {

                $imageName =
                    time() . '_' . uniqid() . '.' . $file->extension();

                $file->move(
                    public_path('uploads/items'),
                    $imageName
                );

                $images[] = $imageName;
            }
        }

        Item::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => json_encode($images),
            'qty' => $request->qty,
            'available_qty' => $request->qty,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status
        ]);

        return redirect()
            ->route('items.index')
            ->with(
                'success',
                'Item Added Successfully'
            );
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);

        $categories = Category::all();

        return view(
            'admin.items.edit',
            compact(
                'item',
                'categories'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

    $oldImages = [];

if (!empty($item->image)) {

    if (is_array($item->image)) {

        $oldImages = $item->image;

    } else {

        $decoded = json_decode($item->image, true);

        if (is_array($decoded)) {
            $oldImages = $decoded;
        } else {
            $oldImages = [$item->image];
        }
    }
}

        // Delete Images
        $deletedImages = json_decode(
            $request->deleted_images,
            true
        ) ?? [];

        foreach ($deletedImages as $index) {

            if (isset($oldImages[$index])) {

                $filePath = public_path(
                    'uploads/items/' . $oldImages[$index]
                );

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                unset($oldImages[$index]);
            }
        }

        $oldImages = array_values($oldImages);

        // Upload New Images
        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $file) {

                $imageName =
                    time() . '_' . uniqid() . '.' . $file->extension();

                $file->move(
                    public_path('uploads/items'),
                    $imageName
                );

                $oldImages[] = $imageName;
            }
        }

        $item->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => json_encode($oldImages),
            'qty' => $request->qty,
            'available_qty' => $request->available_qty,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status
        ]);

        return redirect()
            ->route('items.index')
            ->with(
                'success',
                'Item Updated Successfully'
            );
    }

    public function destroy($id)
    {
        Item::destroy($id);

        return redirect()
            ->back()
            ->with(
                'success',
                'Item Deleted Successfully'
            );
    }
}
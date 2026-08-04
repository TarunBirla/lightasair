<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Category;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with('category')->latest()->get();
        $categories  = Category::where('status', 1)->get();
        return view('admin.commissions.index', compact('commissions', 'categories'));
    }

    public function store(Request $request)
    {
        // Normalize listing_type to lowercase before validation
        if ($request->has('listing_type')) {
            $request->merge(['listing_type' => strtolower($request->listing_type)]);
        }

        $validated = $request->validate([
            'category_id'  => 'nullable|exists:categories,id',
            'listing_type' => 'required|in:sell,rent,auction',
            'rate'         => 'required|numeric|min:0|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }

        Commission::updateOrCreate(
            [
                'category_id'  => $validated['category_id'],
                'listing_type' => $validated['listing_type'],
            ],
            [
                'rate'      => $validated['rate'],
                'is_active' => $validated['is_active'],
            ]
        );

        return redirect()->route('admin.commissions.index')->with('success', 'Commission rate saved successfully.');
    }

    public function update(Request $request, Commission $commission)
    {
        if ($request->has('listing_type')) {
            $request->merge(['listing_type' => strtolower($request->listing_type)]);
        }

        $validated = $request->validate([
            'rate'      => 'required|numeric|min:0|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $commission->update($validated);

        return redirect()->route('admin.commissions.index')->with('success', 'Commission rate updated successfully.');
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();

        return redirect()->route('admin.commissions.index')->with('success', 'Commission rate deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ─── Index: vendor's own listings ─────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Product::where('user_id', Auth::id())
                        ->with('category')
                        ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('listing_type', $request->type);
        }

        $products   = $query->paginate(12);
        $categories = Category::where('status', 1)->get();

        return view('vendor.products.index', compact('products', 'categories'));
    }

    // ─── Create form ──────────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('vendor.products.create', compact('categories'));
    }

    // ─── Store new product ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'condition'         => 'required|in:new,used,refurbished',
            'listing_type'      => 'required|in:sell,rent,auction',
            'price'             => 'nullable|numeric|min:0',
            'rental_price_day'  => 'nullable|numeric|min:0',
            'rental_price_week' => 'nullable|numeric|min:0',
            'deposit_amount'    => 'nullable|numeric|min:0',
            'reserve_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:1',
            'sku'               => 'nullable|string|max:100|unique:products,sku',
            'brand'             => 'nullable|string|max:100',
            'model_number'      => 'nullable|string|max:100',
            'year_manufactured' => 'nullable|integer|min:1900|max:' . date('Y'),
            'location'          => 'nullable|string|max:255',
            'offers_shipping'   => 'boolean',
            'offers_collection' => 'boolean',
            'images'            => 'nullable|array|max:10',
            'images.*'          => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        $validated['user_id']           = Auth::id();
        $validated['images']            = $imagePaths;
        $validated['offers_shipping']   = $request->boolean('offers_shipping');
        $validated['offers_collection'] = $request->boolean('offers_collection');
        $validated['status']            = 'pending'; // goes to admin for approval

        Product::create($validated);

        return redirect()->route('vendor.products.index')
                         ->with('success', 'Your listing has been submitted for admin approval.');
    }

    // ─── Show single (vendor preview) ────────────────────────────────────────
    public function show(Product $product)
    {
        $this->authorizeProduct($product);
        return view('vendor.products.show', compact('product'));
    }

    // ─── Edit form ────────────────────────────────────────────────────────────
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->isApproved()) {
            return redirect()->route('vendor.products.index')
                             ->with('error', 'Approved listings cannot be edited. Please contact support.');
        }

        $categories = Category::where('status', 1)->get();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    // ─── Update existing product ──────────────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'condition'         => 'required|in:new,used,refurbished',
            'listing_type'      => 'required|in:sell,rent,auction',
            'price'             => 'nullable|numeric|min:0',
            'rental_price_day'  => 'nullable|numeric|min:0',
            'rental_price_week' => 'nullable|numeric|min:0',
            'deposit_amount'    => 'nullable|numeric|min:0',
            'reserve_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:1',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'brand'             => 'nullable|string|max:100',
            'model_number'      => 'nullable|string|max:100',
            'year_manufactured' => 'nullable|integer|min:1900|max:' . date('Y'),
            'location'          => 'nullable|string|max:255',
            'offers_shipping'   => 'boolean',
            'offers_collection' => 'boolean',
            'new_images'        => 'nullable|array|max:10',
            'new_images.*'      => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_images'     => 'nullable|array',
            'remove_images.*'   => 'string',
        ]);

        // Handle image removals
        $existingImages = $product->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imgPath) {
                Storage::disk('public')->delete($imgPath);
                $existingImages = array_filter($existingImages, fn($i) => $i !== $imgPath);
            }
        }

        // Handle new uploads
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $existingImages[] = $image->store('products', 'public');
            }
        }

        $validated['images']            = array_values($existingImages);
        $validated['offers_shipping']   = $request->boolean('offers_shipping');
        $validated['offers_collection'] = $request->boolean('offers_collection');
        $validated['status']            = 'pending'; // re-submit for approval after edit

        $product->update($validated);

        return redirect()->route('vendor.products.index')
                         ->with('success', 'Listing updated and re-submitted for approval.');
    }

    // ─── Soft-delete ──────────────────────────────────────────────────────────
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();

        return redirect()->route('vendor.products.index')
                         ->with('success', 'Listing deleted.');
    }

    // ─── Guard: only the owner can manage this product ────────────────────────
    private function authorizeProduct(Product $product): void
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

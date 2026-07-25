<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    // ─── Browse marketplace ───────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Product::approved()
                        ->forSale()
                        ->with(['seller', 'category'])
                        ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('brand', 'LIKE', '%' . $request->search . '%');
            });
        }
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // Sort
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest'     => $query->oldest(),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 1)->get();

        // Featured products for sidebar
        $featured = Product::approved()->forSale()->featured()->latest()->take(4)->get();

        return view('front.marketplace.index', compact('products', 'categories', 'featured'));
    }

    // ─── Product detail ───────────────────────────────────────────────────────
    public function show(Product $product)
    {
        abort_unless($product->isApproved() && $product->isForSale(), 404);

        // Increment view count
        $product->increment('view_count');

        $product->load(['seller.vendorProfile', 'category']);

        // Related products same category
        $related = Product::approved()
                          ->forSale()
                          ->where('id', '!=', $product->id)
                          ->where('category_id', $product->category_id)
                          ->latest()
                          ->take(4)
                          ->get();

        return view('front.marketplace.show', compact('product', 'related'));
    }
}

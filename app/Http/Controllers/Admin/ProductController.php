<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // All products — filterable by status & type
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('listing_type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->paginate(20);

        $stats = [
            'pending'  => Product::pending()->count(),
            'approved' => Product::approved()->count(),
            'total'    => Product::count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    // Show single product detail
    public function show(Product $product)
    {
        $product->load(['seller', 'category', 'approvedBy']);
        return view('admin.products.show', compact('product'));
    }

    // Approve a listing
    public function approve(Product $product)
    {
        $product->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Listing \"{$product->title}\" has been approved.");
    }

    // Reject a listing
    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ]);

        $product->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at'      => null,
            'approved_by'      => null,
        ]);

        return redirect()->back()->with('success', "Listing \"{$product->title}\" has been rejected.");
    }

    // Toggle featured flag
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);
        $msg = $product->is_featured ? 'marked as featured' : 'removed from featured';

        return redirect()->back()->with('success', "Listing \"{$product->title}\" {$msg}.");
    }

    // Hard delete (admin only)
    public function destroy(Product $product)
    {
        $product->forceDelete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Listing permanently deleted.');
    }
}

<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    // Vendor's auctions list
    public function index(Request $request)
    {
        $auctions = Auction::where('user_id', Auth::id())
                           ->with(['category', 'winner'])
                           ->when($request->status, fn($q) => $q->where('status', $request->status))
                           ->latest()
                           ->paginate(12);

        return view('vendor.auctions.index', compact('auctions'));
    }

    // Create form
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('vendor.auctions.create', compact('categories'));
    }

    // Store auction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'condition'         => 'required|in:new,used,refurbished',
            'starting_bid'      => 'required|numeric|min:0.01',
            'reserve_price'     => 'required|numeric|min:0',
            'min_increment'     => 'required|numeric|min:1',
            'start_time'        => 'required|date|after_or_equal:now',
            'end_time'          => 'required|date|after:start_time',
            'location'          => 'nullable|string|max:255',
            'images'            => 'nullable|array|max:10',
            'images.*'          => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imagePaths[] = $img->store('auctions', 'public');
            }
        }

        $validated['user_id']     = Auth::id();
        $validated['images']      = $imagePaths;
        $validated['current_bid'] = $validated['starting_bid'];
        $validated['status']      = 'pending'; // Requires admin approval

        Auction::create($validated);

        return redirect()->route('vendor.auctions.index')
                         ->with('success', 'Auction listing submitted for admin approval.');
    }

    // Show vendor preview & live bids
    public function show(Auction $auction)
    {
        $this->authorizeAuction($auction);
        $auction->load(['category', 'bids.bidder', 'winner']);
        return view('vendor.auctions.show', compact('auction'));
    }

    // Edit form (only allowed if pending or draft)
    public function edit(Auction $auction)
    {
        $this->authorizeAuction($auction);

        if ($auction->isActive() || $auction->isEnded()) {
            return redirect()->route('vendor.auctions.index')
                             ->with('error', 'Active or ended auctions cannot be edited.');
        }

        $categories = Category::where('status', 1)->get();
        return view('vendor.auctions.edit', compact('auction', 'categories'));
    }

    // Update auction
    public function update(Request $request, Auction $auction)
    {
        $this->authorizeAuction($auction);

        if ($auction->isActive() || $auction->isEnded()) {
            return redirect()->route('vendor.auctions.index')
                             ->with('error', 'Active or ended auctions cannot be edited.');
        }

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'condition'         => 'required|in:new,used,refurbished',
            'starting_bid'      => 'required|numeric|min:0.01',
            'reserve_price'     => 'required|numeric|min:0',
            'min_increment'     => 'required|numeric|min:1',
            'start_time'        => 'required|date',
            'end_time'          => 'required|date|after:start_time',
            'location'          => 'nullable|string|max:255',
            'new_images'        => 'nullable|array|max:10',
            'new_images.*'      => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_images'     => 'nullable|array',
        ]);

        $existing = $auction->images ?? [];
        if (is_string($existing)) {
            $existing = json_decode($existing, true) ?: [];
        }

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                $existing = array_filter($existing, fn($i) => $i !== $path);
            }
        }
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $img) {
                $existing[] = $img->store('auctions', 'public');
            }
        }

        $validated['images'] = array_values($existing);
        $validated['status'] = 'pending'; // re-submit for approval

        $auction->update($validated);

        return redirect()->route('vendor.auctions.index')
                         ->with('success', 'Auction updated and re-submitted for approval.');
    }

    // Cancel / Delete
    public function destroy(Auction $auction)
    {
        $this->authorizeAuction($auction);

        if ($auction->bid_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete an auction with active bids.');
        }

        $auction->delete();
        return redirect()->route('vendor.auctions.index')->with('success', 'Auction deleted.');
    }

    private function authorizeAuction(Auction $auction): void
    {
        if ($auction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

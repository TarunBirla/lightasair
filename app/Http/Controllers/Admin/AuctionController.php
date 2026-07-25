<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    // List all auctions
    public function index(Request $request)
    {
        $query = Auction::with(['vendor', 'category', 'winner'])->latest();

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('title', 'LIKE', '%' . $request->search . '%');

        $auctions = $query->paginate(20);

        $stats = [
            'pending' => Auction::pending()->count(),
            'active'  => Auction::active()->count(),
            'ended'   => Auction::ended()->count(),
            'total'   => Auction::count(),
        ];

        return view('admin.auctions.index', compact('auctions', 'stats'));
    }

    // View single auction detail
    public function show(Auction $auction)
    {
        $auction->load(['vendor', 'category', 'bids.bidder', 'winner']);
        return view('admin.auctions.show', compact('auction'));
    }

    // Approve auction
    public function approve(Auction $auction)
    {
        $auction->update([
            'status'      => 'active',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null,
            'start_time'  => $auction->start_time ?? now(),
        ]);

        return back()->with('success', "Auction \"{$auction->title}\" approved and activated.");
    }

    // Reject auction
    public function reject(Request $request, Auction $auction)
    {
        $request->validate(['rejection_reason' => 'required|string|min:10']);

        $auction->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at'      => null,
        ]);

        return back()->with('success', "Auction \"{$auction->title}\" rejected.");
    }

    // Close auction manually
    public function close(Auction $auction)
    {
        $highestBid = $auction->highestBid;

        $winnerId   = null;
        $winningBid = null;

        if ($highestBid && $auction->current_bid >= $auction->reserve_price) {
            $winnerId   = $highestBid->user_id;
            $winningBid = $highestBid->amount;
        }

        $auction->update([
            'status'      => 'ended',
            'winner_id'   => $winnerId,
            'winning_bid' => $winningBid,
        ]);

        return back()->with('success', "Auction \"{$auction->title}\" closed.");
    }

    // Toggle featured
    public function toggleFeatured(Auction $auction)
    {
        $auction->update(['is_featured' => ! $auction->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    // Delete
    public function destroy(Auction $auction)
    {
        $auction->forceDelete();
        return redirect()->route('admin.auctions.index')->with('success', 'Auction deleted.');
    }
}

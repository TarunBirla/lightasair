<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    // Browse active/upcoming auctions
    public function index(Request $request)
    {
        $query = Auction::whereIn('status', ['active', 'ended'])
                        ->with(['vendor', 'category'])
                        ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'ended') {
                $query->ended();
            }
        } else {
            // Default show active first
            $query->active();
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $auctions   = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 1)->get();

        return view('front.auctions.index', compact('auctions', 'categories'));
    }

    // Single auction detail page
    public function show(Auction $auction)
    {
        abort_unless(in_array($auction->status, ['active', 'ended']), 404);

        $auction->increment('view_count');
        $auction->load(['vendor.vendorProfile', 'category', 'bids.bidder', 'winner']);

        return view('front.auctions.show', compact('auction'));
    }

    // Place a bid
    public function bid(Request $request, Auction $auction)
    {
        if (! Auth::check()) {
            return back()->with('error', 'You must be logged in to place a bid.');
        }

        if (! $auction->isActive()) {
            return back()->with('error', 'This auction is not active.');
        }

        if ($auction->user_id === Auth::id()) {
            return back()->with('error', 'You cannot bid on your own auction.');
        }

        $minBid = $auction->nextMinBid();

        $request->validate([
            'amount' => 'required|numeric|min:' . $minBid,
        ], [
            'amount.min' => 'Your bid must be at least £' . number_format($minBid, 2),
        ]);

        DB::transaction(function () use ($auction, $request) {
            // Un-mark previous winning bid
            AuctionBid::where('auction_id', $auction->id)->update(['is_winning' => false]);

            // Create new bid
            AuctionBid::create([
                'auction_id' => $auction->id,
                'user_id'    => Auth::id(),
                'amount'     => $request->amount,
                'ip_address' => $request->ip(),
                'is_winning' => true,
            ]);

            // Update auction current bid & count
            $auction->update([
                'current_bid' => $request->amount,
                'bid_count'   => $auction->bid_count + 1,
            ]);
        });

        return back()->with('success', 'Your bid of £' . number_format($request->amount, 2) . ' has been placed!');
    }

    // AJAX poll for live bids
    public function getBids(Auction $auction)
    {
        $bids = $auction->bids()->with('bidder')->take(10)->get()->map(fn($b) => [
            'bidder'     => Str::mask($b->bidder->name, '*', 2),
            'amount'     => number_format($b->amount, 2),
            'time_ago'   => $b->created_at->diffForHumans(),
            'is_winning' => $b->is_winning,
        ]);

        return response()->json([
            'current_bid' => number_format($auction->current_bid, 2),
            'bid_count'   => $auction->bid_count,
            'is_ended'    => $auction->isEnded(),
            'bids'        => $bids,
        ]);
    }
}

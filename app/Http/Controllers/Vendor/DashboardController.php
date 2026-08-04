<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\RentalListing;
use App\Models\Auction;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->vendorProfile;

        $totalListings = $user->products()->count() + $user->rentalListings()->count() + $user->auctions()->count();
        $activeProducts = $user->products()->where('status', 'approved')->count();
        $activeRentals = $user->rentalListings()->where('status', 'approved')->count();
        $activeAuctions = $user->auctions()->where('status', 'active')->count();

        // Stats placeholders — will be filled when Sell/Rental/Auction modules are built
        $stats = [
            'total_listings'  => $totalListings,
            'active_products' => $activeProducts,
            'active_rentals'  => $activeRentals,
            'active_auctions' => $activeAuctions,
            'total_sales'     => $profile->total_sales ?? 0,
            'total_revenue'   => $profile->total_revenue ?? 0.00,
            'avg_rating'      => $profile->average_rating ?? 0.00,
        ];

        $recentOrders = collect([]); // populated in Module 7
        $recentListings = $user->products()->latest()->take(5)->get();

        return view('vendor.dashboard', compact('user', 'profile', 'stats', 'recentOrders', 'recentListings'));
    }
}

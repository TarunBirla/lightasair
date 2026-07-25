<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->vendorProfile;

        // Stats placeholders — will be filled when Sell/Rental/Auction modules are built
        $stats = [
            'total_listings'  => 0,
            'active_orders'   => 0,
            'total_revenue'   => 0.00,
            'pending_payouts' => 0.00,
            'total_sales'     => $profile->total_sales ?? 0,
            'avg_rating'      => $profile->average_rating ?? 0.00,
        ];

        $recentOrders = collect([]); // populated in Module 7
        $recentListings = collect([]); // populated in Module 2

        return view('vendor.dashboard', compact('user', 'profile', 'stats', 'recentOrders', 'recentListings'));
    }
}

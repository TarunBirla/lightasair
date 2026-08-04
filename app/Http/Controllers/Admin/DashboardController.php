<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\RequestLead;
use App\Models\VendorProfile;
use App\Models\Product;
use App\Models\RentalListing;
use App\Models\Auction;

class DashboardController extends Controller
{
    public function index()
    {
        $bannerCount = Banner::count();

        $categoryCount = Category::count();

        $itemCount = Item::count();

        $requestCount = RequestLead::count();

        $userCount = User::where(
            'role',
            'user'
        )->count();

        $vendorCount = User::where('role', 'vendor')->count();
        $pendingVendors = VendorProfile::where('approval_status', 'pending')->count();
        $productCount = Product::count();
        $rentalCount = RentalListing::count();
        $auctionCount = Auction::count();
        $activeAuctionCount = Auction::where('status', 'active')->count();

        return view(
            'admin.dashboard',
            compact(
                'bannerCount',
                'categoryCount',
                'itemCount',
                'userCount',
                'requestCount',
                'vendorCount',
                'pendingVendors',
                'productCount',
                'rentalCount',
                'auctionCount',
                'activeAuctionCount'
            )
        );
    }
}
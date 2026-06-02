<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\RequestLead;

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

        return view(
            'admin.dashboard',
            compact(
                'bannerCount',
                'categoryCount',
                'itemCount',
                'userCount',
                'requestCount'
            )
        );
    }
}
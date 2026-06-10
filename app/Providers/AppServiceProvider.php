<?php

namespace App\Providers;
use App\Models\Brand;
use App\Models\Portfolio;
use App\Models\Television;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    
    public function boot(): void
{
    View::share(
        'brands',
        Brand::latest()->get()
    );

    View::share(
        'portfolios',
        Portfolio::latest()->get()
    );

    View::share(
        'televisions',
        Television::latest()->get()
    );
     Paginator::useBootstrapFive();
}
}

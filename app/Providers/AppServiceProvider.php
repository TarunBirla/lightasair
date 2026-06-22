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
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {

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

        } catch (\Exception $e) {
            View::share('brands', collect());
            View::share('portfolios', collect());
            View::share('televisions', collect());
        }

        Paginator::useBootstrapFive();
    }
}
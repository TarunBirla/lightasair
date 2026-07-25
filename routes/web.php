<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Front\BookingController as UserBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\TelevisionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\RentalListingController as VendorRentalController;
use App\Http\Controllers\Front\RentalController as FrontRentalController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;


use App\Http\Controllers\Vendor\AuctionController as VendorAuctionController;
use App\Http\Controllers\Front\AuctionController as FrontAuctionController;
use App\Http\Controllers\Admin\AuctionController as AdminAuctionController;

use App\Http\Controllers\Admin\GeneratorBannerController;

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\AuthController as FrontAuthController;

use App\Http\Controllers\Front\MarketplaceController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\ItemController as FrontItemController;


Route::post(
    '/guest-request',
    [HomeController::class,'guestRequest']
);

Route::get(
    '/categories',
    [FrontCategoryController::class,'index']
);
Route::get('/about', function () {
    return view('front.about');
});
Route::get('/brand', function () {
    return view('front.brand');
});
Route::get('/television', function () {
    return view('front.television');
});
Route::get('/portfolio', function () {
    return view('front.portfolio');
});
Route::get('/terms', function () {
    return view('front.terms');
});
Route::get(
    '/category/{id}',
    [FrontCategoryController::class,'show']
);

Route::get(
    '/items',
    [FrontItemController::class,'index']
);
Route::get('/', [HomeController::class, 'index']);

Route::get(
    '/item/{id}',
    [HomeController::class, 'itemDetail']
);

Route::middleware('auth')->group(function () {

    Route::post(
        '/add-to-cart',
        [UserBookingController::class, 'addToCart']
    );
Route::get(
    '/cart/increase/{id}',
    [UserBookingController::class,'increaseQty']
)->name('cart.increase');

Route::get(
    '/cart/decrease/{id}',
    [UserBookingController::class,'decreaseQty']
)->name('cart.decrease');
    Route::get(
        '/cart',
        [UserBookingController::class, 'cart']
    );

    Route::get(
        '/checkout',
        [UserBookingController::class, 'checkoutPage']
    );

    Route::post(
        '/place-order',
        [UserBookingController::class, 'placeOrder']
    );

    Route::get(
        '/my-bookings',
        [UserBookingController::class, 'myBookings']
    );

    Route::get(
        '/booking/{id}',
        [UserBookingController::class, 'bookingDetail']
    );
    Route::get(
        '/cart/remove/{id}',
        [UserBookingController::class, 'removeCart']
    )->name('cart.remove');
});

Route::get('/login', [FrontAuthController::class, 'login']);
Route::post('/login', [FrontAuthController::class, 'loginSubmit']);

Route::get('/register', [FrontAuthController::class, 'register']);
Route::post('/register', [FrontAuthController::class, 'registerSubmit']);

Route::get('/logout', [FrontAuthController::class, 'logout']);

Route::get('/vendor/pending', [FrontAuthController::class, 'vendorPending'])->middleware('auth');


Route::get('/profile', [FrontAuthController::class, 'profile'])
    ->middleware('auth');

// ─── Vendor Panel ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        // Product (Sell) listings
        Route::resource('products', VendorProductController::class);

        // ─── Rental listings ──────────────────────────────────────────────
        Route::resource('rentals', VendorRentalController::class);
        // Calendar management
        Route::get('rentals/{rental}/calendar',       [VendorRentalController::class, 'calendar'])->name('vendor.rentals.calendar');
        Route::post('rentals/{rental}/block-date',    [VendorRentalController::class, 'blockDate'])->name('vendor.rentals.block-date');
        Route::post('rentals/{rental}/unblock-date',  [VendorRentalController::class, 'unblockDate'])->name('vendor.rentals.unblock-date');
        // Rental bookings (vendor side)
        Route::get('rental-bookings',                         [VendorRentalController::class, 'bookings'])->name('vendor.rental-bookings');
        Route::post('rental-bookings/{booking}/status',       [VendorRentalController::class, 'updateBookingStatus'])->name('vendor.rental-bookings.status');
        // ─── Auction listings ─────────────────────────────────────────────
        Route::resource('auctions', VendorAuctionController::class);
    });

// ─── Front: Sell Marketplace ────────────────────────────────────────────────
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('front.marketplace');
Route::get('/marketplace/{product:slug}', [MarketplaceController::class, 'show'])->name('front.marketplace.show');

// ─── Front: Rental Marketplace ─────────────────────────────────────────────
Route::get('/rentals', [FrontRentalController::class, 'index'])->name('front.rentals');
Route::get('/rentals/{rental:slug}', [FrontRentalController::class, 'show'])->name('front.rentals.show');
Route::post('/rentals/{rental}/check-availability', [FrontRentalController::class, 'checkAvailability'])->name('front.rentals.check-availability');
Route::middleware('auth')->group(function () {
    Route::post('/rentals/{rental}/book',          [FrontRentalController::class, 'book'])->name('front.rentals.book');
    Route::get('/my-rentals',                      [FrontRentalController::class, 'myRentals'])->name('front.my-rentals');
    Route::post('/my-rentals/{booking}/cancel',    [FrontRentalController::class, 'cancel'])->name('front.rentals.cancel');
});

// ─── Front: Auction Marketplace ───────────────────────────────────────────
Route::get('/auctions', [FrontAuctionController::class, 'index'])->name('front.auctions');
Route::get('/auctions/{auction:slug}', [FrontAuctionController::class, 'show'])->name('front.auctions.show');
Route::post('/auctions/{auction}/bid', [FrontAuctionController::class, 'bid'])->name('front.auctions.bid');
Route::get('/auctions/{auction}/get-bids', [FrontAuctionController::class, 'getBids'])->name('front.auctions.get-bids');

Route::get(
    '/admin/login',
    [AuthController::class, 'login']
);

Route::post(
    '/admin/login',
    [AuthController::class, 'loginSubmit']
);



Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

    Route::resource(
    'brands',
    BrandController::class
);
Route::resource(
    'generator-banners',
    GeneratorBannerController::class
);

Route::resource(
    'portfolios',
    PortfolioController::class
);

Route::resource(
    'televisions',
    TelevisionController::class
);
Route::delete(
    '/category-image/{id}',
    [CategoryController::class,'deleteImage']
)->name('category.image.delete');

    Route::get(
        '/bookings',
        [BookingController::class,'index']
    )->name('admin.bookings');

    Route::get(
        '/users',
        [BookingController::class,'users']
    )->name('admin.users');
    Route::get(
    '/admin/booking/{id}',
    [BookingController::class,'show']
)->name('admin.booking.show');
Route::get(
'/admin/booking/{id}/approve',
[BookingController::class,'approve']
)->name('admin.booking.approve');

Route::get(
'/admin/booking/{id}/reject',
[BookingController::class,'reject']
)->name('admin.booking.reject');

Route::get(
'/admin/booking/{id}/complete',
[BookingController::class,'complete']
)->name('admin.booking.complete');

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        );

        Route::resource(
            'banner',
            BannerController::class
        );

      Route::get(
    '/requests',
    [BookingController::class,'requests']
)->name('admin.requests');
Route::delete(
    '/requests/{id}',
    [BookingController::class,'deleteRequest']
)->name('admin.requests.delete');

        Route::resource(
            'category',
            CategoryController::class
        );

        Route::resource(
            'items',
            ItemController::class
        );

        Route::get(
            'logout',
            [AuthController::class, 'logout']
        );

        // ─── Admin: Product approval ──────────────────────────────────────────
        Route::resource('products', AdminProductController::class)->except(['create', 'edit']);
        Route::post('products/{product}/approve',         [AdminProductController::class, 'approve'])->name('admin.products.approve');
        Route::post('products/{product}/reject',          [AdminProductController::class, 'reject'])->name('admin.products.reject');
        Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('admin.products.toggle-featured');

        // ─── Admin: Rental listings ───────────────────────────────────────
        Route::resource('rentals', AdminRentalController::class)->except(['create', 'edit']);
        Route::post('rentals/{rental}/approve',        [AdminRentalController::class, 'approve'])->name('admin.rentals.approve');
        Route::post('rentals/{rental}/reject',         [AdminRentalController::class, 'reject'])->name('admin.rentals.reject');
        Route::post('rentals/{rental}/toggle-featured',[AdminRentalController::class, 'toggleFeatured'])->name('admin.rentals.toggle-featured');
        Route::get('rental-bookings',                  [AdminRentalController::class, 'bookings'])->name('admin.rental-bookings');

        // ─── Admin: Auction listings ──────────────────────────────────────
        Route::resource('auctions', AdminAuctionController::class)->except(['create', 'edit']);
        Route::post('auctions/{auction}/approve',        [AdminAuctionController::class, 'approve'])->name('admin.auctions.approve');
        Route::post('auctions/{auction}/reject',         [AdminAuctionController::class, 'reject'])->name('admin.auctions.reject');
        Route::post('auctions/{auction}/close',          [AdminAuctionController::class, 'close'])->name('admin.auctions.close');
        Route::post('auctions/{auction}/toggle-featured',[AdminAuctionController::class, 'toggleFeatured'])->name('admin.auctions.toggle-featured');

    });





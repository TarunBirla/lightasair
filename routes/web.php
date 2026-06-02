<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Front\BookingController as UserBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;



use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\AuthController as FrontAuthController;

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

Route::get('/profile', [FrontAuthController::class, 'profile'])
    ->middleware('auth');

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

    });





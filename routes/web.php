<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\AccountController as ClientAccountController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\PageController;
use App\Http\Controllers\Client\PostController as ClientPostController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\WishlistController as ClientWishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('tai-khoan')->name('client.')->controller(ClientAuthController::class)->group(function () {
    Route::get('dang-nhap', 'showLogin')->name('login');
    Route::post('dang-nhap', 'login')->name('login.submit');
    Route::get('dang-ky', 'showRegister')->name('register');
    Route::post('dang-ky', 'register')->name('register.submit');
    Route::post('dang-xuat', 'logout')->middleware('auth')->name('logout');
});

Route::controller(ClientProductController::class)->group(function () {
    Route::get('/san-pham', 'index')->name('product.index');
    Route::get('/san-pham/{slug}', 'show')->name('product.show');
    Route::get('/danh-muc/{slug}', 'category')->name('product.category');
    Route::get('/thuong-hieu/{slug}', 'brand')->name('product.brand');
    Route::get('/tim-kiem', 'search')->name('product.search');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/lien-he', 'contact')->name('contact');
    Route::post('/lien-he', 'submitContact')->name('contact.submit');
});

Route::controller(ClientPostController::class)->group(function () {
    Route::get('/bai-viet', 'index')->name('post.index');
    Route::get('/bai-viet/{slug}', 'show')->name('post.show');
});

Route::prefix('cart')->controller(CartController::class)->name('cart.')->group(function () {
    Route::get('/show', 'show')->name('show');
    Route::post('/add/{id}', 'addToCart')->name('add');
    Route::post('/update/{key}', 'updateQuantity')->name('update');
    Route::delete('/remove/{key}', 'removeCart')->name('remove');
    Route::post('/coupon', 'applyCoupon')->name('coupon.apply');
    Route::post('/coupon/remove', 'removeCoupon')->name('coupon.remove');
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/save', 'save')->name('save');
    });
});

Route::prefix('don-hang')->middleware('auth')->name('order.')->controller(ClientOrderController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/{id}/thanh-toan-demo', 'payDemo')->name('pay.demo');
});

Route::prefix('yeu-thich')->middleware('auth')->name('wishlist.')->controller(ClientWishlistController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{productId}', 'add')->name('add');
    Route::delete('/{productId}', 'remove')->name('remove');
});

Route::prefix('tai-khoan')->middleware('auth')->name('account.')->controller(ClientAccountController::class)->group(function () {
    Route::get('/', 'edit')->name('edit');
    Route::put('/thong-tin', 'updateProfile')->name('profile.update');
    Route::put('/mat-khau', 'updatePassword')->name('password.update');
});

Route::prefix('admin')->middleware(['auth', 'roles:1'])->name('admin.')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //Product
    Route::prefix('products')
        ->name('product.')
        ->controller(ProductController::class)
        ->group(function () {
            Route::get('/', 'index2')->name('index');
            Route::get('/create', 'create')->middleware('roles:1')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });
    
    //Category
    Route::prefix('categories')
        ->name('cate.')
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    //Brand
    Route::prefix('brands')
        ->name('brand.')
        ->controller(BrandController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    //User
    Route::prefix('users')
        ->name('user.')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::get('/{id}/password', 'editPassword')->name('password.edit');
            Route::put('/{id}/password', 'updatePassword')->name('password.update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    //Post
    Route::prefix('posts')
        ->name('post.')
        ->controller(PostController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    Route::prefix('banners')
        ->name('banner.')
        ->controller(AdminBannerController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    Route::prefix('orders')
        ->name('order.')
        ->controller(AdminOrderController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::put('/{id}/status', 'updateStatus')->name('status.update');
            Route::put('/{id}/payment-status', 'updatePaymentStatus')->name('payment-status.update');
        });

    Route::prefix('contacts')
        ->name('contact.')
        ->controller(AdminContactMessageController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::delete('/{id}', 'destroy')->name('del');
        });

    Route::prefix('coupons')
        ->name('coupon.')
        ->controller(AdminCouponController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('del');
        });

});

Route::prefix('admin')->name('admin.')->controller(AuthController::class)->group(function () {
    //Auth
    Route::get('login', 'showLogin')->name('login');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
    Route::get('forgot', 'showForgot')->name('forgot');
    Route::post('forgot', 'forgot')->name('forgot.submit');

});

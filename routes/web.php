<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Shop
Route::prefix('/shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');
});

// Cart
Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
    Route::post('/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');
});

// Wishlist
Route::prefix('/wishlist')->group(function () {
    Route::post('/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
});

// Checkout
Route::prefix('checkout')->group(function () {
    Route::get('/', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
    Route::get('/order-confirmation/{order}', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');
});

// Contact
Route::prefix('contact')->group(function () {
    Route::get('/', [HomeController::class, 'contact'])->name('home.contact');
    Route::post('/store', [HomeController::class, 'contact_store'])->name('home.contact.store');
});

// Search
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-order/{order_id}/details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::post('/account-order/cancel-order', [UserController::class, 'order_cancel'])->name('user.order.cancel');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('admin')->group(function () {
        // Brands
        Route::prefix('brands')->group(function () {
            Route::get('/', [AdminController::class, 'brands'])->name('admin.brands');
            Route::get('/add', [AdminController::class, 'add_brand'])->name('admin.brands.add');
            Route::post('/store', [AdminController::class, 'brand_store'])->name('admin.brands.store');
            Route::get('/{id}/edit', [AdminController::class, 'brand_edit'])->name('admin.brands.edit');
            Route::post('/update', [AdminController::class, 'brand_update'])->name('admin.brands.update');
            Route::post('/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brands.delete');
        });

        //Categories
        Route::prefix('categories')->group(function () {
            Route::get('/', [AdminController::class, 'categories'])->name('admin.categories');
            Route::get('/add', [AdminController::class, 'category_add'])->name('admin.categories.add');
            Route::post('/store', [AdminController::class, 'category_store'])->name('admin.categories.store');
            Route::get('/{id}/edit', [AdminController::class, 'category_edit'])->name('admin.categories.edit');
            Route::post('/update', [AdminController::class, 'category_update'])->name('admin.categories.update');
            Route::post('/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.categories.delete');
        });

        //Products
        Route::prefix('products')->group(function () {
            Route::get('/', [AdminController::class, 'products'])->name('admin.products');
            Route::get('/add', [AdminController::class, 'product_add'])->name('admin.products.add');
            Route::post('/store', [AdminController::class, 'product_store'])->name('admin.products.store');
            Route::get('/{id}/edit', [AdminController::class, 'product_edit'])->name('admin.products.edit');
            Route::post('/update', [AdminController::class, 'product_update'])->name('admin.products.update');
            Route::post('/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.products.delete');
        });

        //Coupons
        Route::prefix('/coupons')->group(function () {
            Route::get('/', [AdminController::class, 'coupons'])->name('admin.coupons');
            Route::get('/add', [AdminController::class, 'coupon_add'])->name('admin.coupons.add');
            Route::post('/store', [AdminController::class, 'coupon_store'])->name('admin.coupons.store');
            Route::get('/{id}/edit', [AdminController::class, 'coupon_edit'])->name('admin.coupons.edit');
            Route::post('/update', [AdminController::class, 'coupon_update'])->name('admin.coupons.update');
            Route::post('/{id}/delete', [AdminController::class, 'coupon_delete'])->name('admin.coupons.delete');
        });

        //Orders
        Route::prefix('/orders')->group(function () {
            Route::get('/', [AdminController::class, 'orders'])->name('admin.orders');
            Route::get('/order/{id}/details', [AdminController::class, 'order_details'])->name('admin.orders.order-details');
            Route::post('/update-status', [AdminController::class, 'update_order_status'])->name('admin.orders.status.update');
        });

        //Slides
        Route::prefix('/slides')->group(function () {
            Route::get('/', [AdminController::class, 'slides'])->name('admin.slides.index');
            Route::get('/add', [AdminController::class, 'slide_add'])->name('admin.slides.add');
            Route::post('/store', [AdminController::class, 'slide_store'])->name('admin.slides.store');
            Route::get('/{id}/edit', [AdminController::class, 'slide_edit'])->name('admin.slides.edit');
            Route::post('/update', [AdminController::class, 'slide_update'])->name('admin.slides.update');
            Route::post('/{id}/delete', [AdminController::class, 'slide_delete'])->name('admin.slides.delete');
        });

        //Contacts
        Route::prefix('/contacts')->group(function () {
            Route::get('/', [AdminController::class, 'contacts'])->name('admin.contacts.index');
            Route::post('/{id}/delete', [AdminController::class, 'contact_delete'])->name('admin.contacts.delete');
        });
    });
});
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::prefix('/shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');
});

Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
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
    });
});
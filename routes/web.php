<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PHPUnit\Metadata\Group;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('admin')->group(function () {
        // Brands
        Route::get('/brands', [AdminController::class, 'brands'])->name('admin.brands');
        Route::get('/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
        Route::post('/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
        Route::get('/brand/{id}/edit', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
        Route::post('/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
        Route::post('/brand/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

        //Categories
        Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::get('/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
        Route::post('/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
        Route::get('/category/{id}/edit', [AdminController::class, 'category_edit'])->name('admin.category.edit');
        Route::post('/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
        Route::post('/category/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.category.delete');

        //Products
        Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    });
});
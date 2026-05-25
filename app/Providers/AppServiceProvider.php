<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $wishlistCount = 0;

            try {
                if (Auth::check()) {
                    $wishlist = Cart::where('user_id', Auth::id())
                        ->where('type', 'wishlist')
                        ->withCount('items')
                        ->first();
                } else {
                    $wishlist = Cart::where('session_id', session()->getId())
                        ->where('type', 'wishlist')
                        ->withCount('items')
                        ->first();
                }

                $wishlistCount = $wishlist ? $wishlist->items_count : 0;
            } catch (\Exception $e) {
                $wishlistCount = 0;
            }

            $view->with('wishlistCount', $wishlistCount);
        });

        View::composer('layouts.app', function ($view) {
            $view->with('categories', Category::all());
        });
    }
}
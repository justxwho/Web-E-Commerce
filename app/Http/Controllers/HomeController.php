<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Slide;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured', 1)->get()->take(8);
        $cart = null;
        $wishlistCart = null;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->where('type', 'cart')
                ->with('items')
                ->first();

            $wishlistCart = Cart::where('user_id', Auth::id())
                ->where('type', 'wishlist')
                ->with('items')
                ->first();
        }

        return view('index', compact('slides', 'categories', 'sproducts', 'fproducts', 'cart', 'wishlistCart'));
    }
}
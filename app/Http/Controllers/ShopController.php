<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        } else {
            $cart = \App\Models\Cart::where('session_id', session()->getId())->first();
        }
        $items = $cart ? $cart->items : collect();

        return view('shop', compact('products', 'items'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->firstOrFail();

        $rproducts = Product::where('slug', '<>', $product_slug)
            ->take(8)
            ->get();

        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        } else {
            $cart = \App\Models\Cart::where('session_id', session()->getId())->first();
        }

        $items = $cart ? $cart->items()->get() : collect();

        return view('details', compact('product', 'rproducts', 'items'));
    }
}
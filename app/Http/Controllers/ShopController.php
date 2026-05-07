<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 12);
        $order = $request->query('order', -1);

        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');

        $min_price = $request->query('min', 1);
        $max_price = $request->query('max', 500);

        // SORT
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'regular_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'regular_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
        }

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        // QUERY BUILDER
        $products = Product::query();

        // FILTER BRAND
        if (!empty($f_brands)) {
            $products->whereIn('brand_id', explode(',', $f_brands));
        }

        // FILTER CATEGORY
        if (!empty($f_categories)) {
            $products->whereIn('category_id', explode(',', $f_categories));
        }

        // FILTER PRICE
        $products->where(function ($query) use ($min_price, $max_price) {
            $query->whereBetween('regular_price', [$min_price, $max_price])
                ->orWhereBetween('sale_price', [$min_price, $max_price]);
        });

        // FINAL
        $products = $products->orderBy($o_column, $o_order)
            ->paginate($size)
            ->withQueryString();

        // CART
        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        } else {
            $cart = \App\Models\Cart::where('session_id', session()->getId())->first();
        }

        $items = $cart ? $cart->items : collect();

        //WISHLISH
        $wishlistCart = Auth::check()
            ? Cart::where('user_id', Auth::id())->where('type', 'wishlist')->with('items')->first()
            : Cart::where('session_id', session()->getId())->where('type', 'wishlist')->with('items')->first();


        return view('shop', compact(
            'products',
            'items',
            'cart',
            'size',
            'order',
            'brands',
            'f_brands',
            'categories',
            'f_categories',
            'min_price',
            'max_price',
            'wishlistCart'
        ));
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
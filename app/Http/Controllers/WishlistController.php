<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function add_to_wishlist(Request $request)
    {
        $wishlist = Auth::check()
            ? Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'type' => 'wishlist'
            ])
            : Cart::firstOrCreate([
                'session_id' => session()->getId(),
                'type' => 'wishlist'
            ]);

        $product = Product::findOrFail($request->id);

        $price = $product->sale_price
            ? $product->sale_price
            : $product->regular_price;

        $item = CartItem::where('cart_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $wishlist->id,
                'product_id' => $product->id,
                'price' => $price,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Added to wishlist');
    }
}
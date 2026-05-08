<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function getCart($type = 'wishlist')
    {
        if (Auth::check()) {
            return Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'type' => $type
            ]);
        }

        return Cart::firstOrCreate([
            'session_id' => session()->getId(),
            'type' => $type
        ]);
    }

    public function index()
    {
        $cart = $this->getCart('wishlist');

        $items = $cart
            ? CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get()
            : collect();

        $shoppingCart = $this->getCart('cart')->load('items');

        return view('wishlist', compact('items', 'cart', 'shoppingCart'));
    }

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

    public function remove($id)
    {
        $cart = $this->getCart('wishlist');

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item removed');
    }

    public function clear()
    {
        $cart = $this->getCart('wishlist');

        CartItem::where('cart_id', $cart->id)->delete();

        return back()->with('success', 'Wishlist cleared');
    }
}

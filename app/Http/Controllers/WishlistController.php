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
        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        if (Auth::check()) {
            $wishlist = Cart::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'type' => 'wishlist'
                ],
                [
                    'session_id' => null
                ]
            );
        } else {
            $wishlist = Cart::firstOrCreate(
                [
                    'session_id' => session()->getId(),
                    'type' => 'wishlist'
                ],
                [
                    'user_id' => null
                ]
            );
        }

        $product = Product::findOrFail($request->id);

        $item = CartItem::firstOrNew([
            'cart_id' => $wishlist->id,
            'product_id' => $product->id
        ]);

        $item->price = $product->price;

        if ($item->exists) {
            $item->quantity += ($request->quantity ?? 1);
        } else {
            $item->quantity = $request->quantity ?? 1;
        }

        $item->save();

        return back()->with('success', 'Added to wishlist');
    }
}
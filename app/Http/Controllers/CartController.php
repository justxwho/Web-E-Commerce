<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        }

        return Cart::firstOrCreate([
            'session_id' => session()->getId()
        ]);
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = $this->getCart();

        $price = $product->sale_price ?? $product->regular_price;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $id,
                'quantity' => 1,
                'price' => $price
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function index()
    {
        $cart = $this->getCart()->load('items.product');

        return view('cart', [
            'cart' => $cart,
            'items' => $cart->items
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $item->quantity = $request->quantity;
        $item->save();

        return back()->with('success', 'Cart updated');
    }

    public function remove($id)
    {
        $cart = $this->getCart();

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item removed');
    }

    public function clear()
    {
        $cart = $this->getCart();

        CartItem::where('cart_id', $cart->id)->delete();

        return back()->with('success', 'Cart cleared');
    }
}
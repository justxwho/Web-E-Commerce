<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart($type = 'cart')
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

    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = $this->getCart();

        $price = $product->sale_price ?? $product->regular_price;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->first();

        if ($item) {
            $item->increment('quantity');
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

        return view('cart', compact('cart'));
    }

    public function increase($id)
    {
        $cart = $this->getCart();

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $item->increment('quantity');

        $cart->load('items');

        return response()->json([
            'qty' => $item->quantity,
            'item_subtotal' => $item->subtotal,
            'cart_subtotal' => $cart->subtotal,
            'vat' => $cart->vat,
            'total' => $cart->final_total
        ]);
    }

    public function decrease($id)
    {
        $cart = $this->getCart();

        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        $cart->load('items');

        return response()->json([
            'qty' => $item->quantity ?? 0,
            'item_subtotal' => $item->subtotal ?? 0,
            'cart_subtotal' => $cart->subtotal,
            'vat' => $cart->vat,
            'total' => $cart->final_total
        ]);
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
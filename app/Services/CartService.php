<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart($type = 'cart')
    {
        return Auth::check()
            ? Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'type' => $type
            ])
            : Cart::firstOrCreate([
                'session_id' => session()->getId(),
                'type' => $type
            ]);
    }

    public function add($productId, $price, $qty = 1, $type = 'cart')
    {
        $cart = $this->getCart($type);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->increment('quantity', $qty);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $qty
            ]);
        }

        return $cart->fresh('items');
    }

    public function updateQty($itemId, $type = 'cart', $action = 'increase')
    {
        $cart = $this->getCart($type);

        $item = CartItem::where('id', $itemId)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        if ($action === 'increase') {
            $item->increment('quantity');
        } else {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
            } else {
                $item->delete();
            }
        }

        return $cart->fresh('items');
    }

    public function count($type = 'cart')
    {
        $cart = $this->getCart($type);
        return $cart->items()->sum('quantity');
    }
}
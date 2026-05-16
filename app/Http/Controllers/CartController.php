<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Address;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
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

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;

        if (!$coupon_code) {
            return redirect()->back()->with('error', 'Please enter a coupon code.');
        }

        $cart = $this->getCart()->load('items');

        $coupon = Coupon::where('code', $coupon_code)
            ->where('expiry_date', '>=', Carbon::today())
            ->where('cart_value', '<=', $cart->subtotal)
            ->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid or expired coupon code.');
        }

        Session::put('coupon', [
            'code'       => $coupon->code,
            'type'       => $coupon->type,
            'value'      => $coupon->value,
            'cart_value' => $coupon->cart_value,
        ]);

        $this->calculateDiscount($cart);

        return redirect()->back()->with('success', 'Coupon "' . $coupon->code . '" has been applied!');
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');

        return redirect()->back()->with('success', 'Coupon removed.');
    }

    private function calculateDiscount(Cart $cart)
    {
        $coupon   = Session::get('coupon');
        $subtotal = $cart->subtotal;

        if ($coupon['type'] === 'fixed') {
            $discount = (float) $coupon['value'];
        } else {
            // percentage
            $discount = ($subtotal * (float) $coupon['value']) / 100;
        }

        $subtotalAfterDiscount = max(0, $subtotal - $discount);
        $taxAfterDiscount = $subtotalAfterDiscount * 0.1;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        Session::put('discounts', [
            'discount' => number_format($discount, 2, '.', ''),
            'subtotal' => number_format($subtotalAfterDiscount, 2, '.', ''),
            'tax' => number_format($taxAfterDiscount, 2, '.', ''),
            'total' => number_format($totalAfterDiscount, 2, '.', ''),
        ]);
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = $this->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $address = Address::where('user_id', Auth::id())
            ->where('isdefault', 1)
            ->first();

        return view('checkout', compact('cart', 'address'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use League\CommonMark\Node\Query\OrExpr;

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

    public function place_an_order(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:card,paypal,cod',
        ]);

        $user_id = Auth::id();
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        if (!$address) {
            $request->validate([
                'name'     => 'required|max:100',
                'phone'    => 'required|numeric|digits:10',
                'zip'      => 'required|numeric|digits:6',
                'state'    => 'required',
                'city'     => 'required',
                'address'  => 'required',
                'locality' => 'required',
                'landmark' => 'nullable|max:255',
            ]);

            $address           = new Address();
            $address->name     = $request->name;
            $address->phone    = $request->phone;
            $address->zip      = $request->zip;
            $address->state    = $request->state;
            $address->city     = $request->city;
            $address->address  = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country  = 'Viet Nam';
            $address->user_id  = $user_id;
            $address->isdefault = true;
            $address->save();
        }

        $cart = $this->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $this->setAmountforCheckout($cart);
        $checkout = Session::get('checkout');

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = $checkout['subtotal'];
        $order->discount = $checkout['discount'];
        $order->tax = $checkout['tax'];
        $order->total = $checkout['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->address = $address->locality . ', ' . $address->address . ', ' . $address->state;
        $order->city = $address->city;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        foreach ($cart->items as $item) {
            OrderItem::create([
                'product_id' => $item->product_id,
                'order_id'   => $order->id,
                'price'      => $item->price,
                'quantity'   => $item->quantity,
            ]);
        }

        if ($request->mode === 'cod') {
            Transaction::create([
                'user_id'  => $user_id,
                'order_id' => $order->id,
                'mode'     => $request->mode,
                'status'   => 'pending',
            ]);
        } else if ($request->mode === 'card') {
            //
        } else if ($request->mode === 'paypal') {
            //
        }

        CartItem::where('cart_id', $cart->id)->delete();
        Session::forget(['checkout', 'coupon', 'discounts']);

        return redirect()->route('cart.order.confirmation', ['order' => $order->id]);
    }

    public function setAmountforCheckout(Cart $cart)
    {
        if ($cart->items->isEmpty()) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => $cart->subtotal,
                'tax' => $cart->vat,
                'total' => $cart->final_total,
            ]);
        }
    }

    public function order_confirmation(Order $order)
    {
        $order->load('orderItems.product', 'transaction');
        return view('order-confirmation', compact('order'));
    }
}
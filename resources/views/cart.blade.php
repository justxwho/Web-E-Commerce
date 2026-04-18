@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Cart</h2>
            <div class="checkout-steps">
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                    width="120" height="120" alt="{{ $item->product->name }}">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->product->name }}</h4>
                                            </div>
                                        </td>

                                        <td>
                                            ${{ number_format($item->price, 2) }}
                                        </td>

                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                    min="1" class="qty-control__number text-center"
                                                    style="width:70px;">
                                            </form>
                                        </td>

                                        <td>
                                            ${{ number_format($item->subtotal, 2) }}
                                        </td>

                                        <td>
                                            <a href="{{ route('cart.remove', $item->id) }}" class="remove-cart"
                                                onclick="return confirm('Remove item?')">
                                                ❌
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Cart is empty</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            <form action="#" class="position-relative bg-body">
                                <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
                                <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                                    value="APPLY COUPON">
                            </form>
                            <button class="btn btn-light">UPDATE CART</button>
                        </div>
                    </div>
                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Cart Totals</h3>
                                <table class="cart-totals">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>$1300</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input form-check-input_fill" type="checkbox"
                                                        value="" id="free_shipping">
                                                    <label class="form-check-label" for="free_shipping">Free
                                                        shipping</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input form-check-input_fill" type="checkbox"
                                                        value="" id="flat_rate">
                                                    <label class="form-check-label" for="flat_rate">Flat rate: $49</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input form-check-input_fill" type="checkbox"
                                                        value="" id="local_pickup">
                                                    <label class="form-check-label" for="local_pickup">Local pickup:
                                                        $8</label>
                                                </div>
                                                <div>Shipping to AL.</div>
                                                <div>
                                                    <a href="#" class="menu-link menu-link_us-s">CHANGE ADDRESS</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VAT</th>
                                            <td>$19</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>$1319</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    <a href="checkout.html" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>No item found in your cart.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">Shop Now</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

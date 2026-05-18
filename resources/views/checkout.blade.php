@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Shipping and Checkout</h2>
            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item active">
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

            {{-- Flash messages --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>SHIPPING DETAILS</h4>
                            </div>
                        </div>
                        @if ($address)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-account__address-list">
                                        <div class="my-account__address-item__detail">
                                            <p>{{ $address->name }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->landmark }}</p>
                                            <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
                                            <p>{{ $address->zip }}</p>
                                            <br>
                                            <p>{{ $address->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                                            required>
                                        <label for="name">Full Name *</label>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" id="phone" value="{{ old('phone', Auth::user()->mobile) }}"
                                            required>
                                        <label for="phone">Phone Number *</label>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                            name="zip" id="zip" value="{{ old('zip', $address->zip ?? '') }}"
                                            required>
                                        <label for="zip">Pincode *</label>
                                        @error('zip')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mt-3 mb-3">
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            name="state" id="state" value="{{ old('state', $address->state ?? '') }}"
                                            required>
                                        <label for="state">State *</label>
                                        @error('state')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            name="city" id="city" value="{{ old('city', $address->city ?? '') }}"
                                            required>
                                        <label for="city">Town / City *</label>
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            name="address" id="address"
                                            value="{{ old('address', $address->address ?? '') }}" required>
                                        <label for="address">House no, Building Name *</label>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('locality') is-invalid @enderror"
                                            name="locality" id="locality"
                                            value="{{ old('locality', $address->locality ?? '') }}" required>
                                        <label for="locality">Road Name, Area, Colony *</label>
                                        @error('locality')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('landmark') is-invalid @enderror"
                                            name="landmark" id="landmark"
                                            value="{{ old('landmark', $address->landmark ?? '') }}">
                                        <label for="landmark">Landmark</label>
                                        @error('landmark')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Order Summary --}}
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>Your Order</h3>
                                <table class="checkout-cart-items">
                                    <thead>
                                        <tr>
                                            <th>PRODUCT</th>
                                            <th align="right">SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->name }} x {{ $item->quantity }}
                                                </td>
                                                <td align="right">
                                                    ${{ number_format($item->subtotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <table class="checkout-totals">
                                    <tbody>
                                        @if (session('discounts'))
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td align="right">${{ number_format($cart->subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>DISCOUNT ({{ session('coupon.code') }})</th>
                                                <td align="right" class="text-danger">
                                                    -${{ session('discounts.discount') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>SHIPPING</th>
                                                <td align="right">Free shipping</td>
                                            </tr>
                                            <tr>
                                                <th>VAT (10%)</th>
                                                <td align="right">${{ session('discounts.tax') }}</td>
                                            </tr>
                                            <tr>
                                                <th>TOTAL</th>
                                                <td align="right">${{ session('discounts.total') }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td align="right">${{ number_format($cart->subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>SHIPPING</th>
                                                <td align="right">Free shipping</td>
                                            </tr>
                                            <tr>
                                                <th>VAT (10%)</th>
                                                <td align="right">${{ number_format($cart->vat, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>TOTAL</th>
                                                <td align="right">${{ number_format($cart->final_total, 2) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Payment Methods --}}
                            <div class="checkout__payment-methods">

                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode1" value="card" required>
                                    <label class="form-check-label" for="mode1">
                                        Credit card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode2" value="paypal" required>
                                    <label class="form-check-label" for="mode2">
                                        Paypal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode3" value="cod" required>
                                    <label class="form-check-label" for="mode3">
                                        Cash on delivery
                                    </label>
                                </div>

                                @error('payment_method')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="policy-text">
                                    Your personal data will be used to process your order, support your experience
                                    throughout this website, and for other purposes described in our
                                    <a href="terms.html" target="_blank">privacy policy</a>.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-checkout">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection

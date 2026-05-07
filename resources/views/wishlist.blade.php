@extends('layouts.app')
@section('content')
    <style>
        #header {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .logo__image {
            max-width: 220px;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Wishlist</h2>
            <div class="checkout-steps">
                <a href="shop_cart.html" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="shop_checkout.html" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="shop_order_complete.html" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @php
                    $items = $cart ? $cart->items : collect();
                @endphp
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center"></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                    width="120" height="120" alt="{{ $item->product->name }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->product->name }}</h4>
                                                {{-- <ul class="shopping-cart__product-item__options">
                                                <li>Color: Yellow</li>
                                                <li>Size: L</li>
                                            </ul> --}}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="shopping-cart__product-price">
                                                {{ $item->product->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="shopping-cart__product-price">${{ $item->price }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($shoppingCart && $shoppingCart->items->contains('product_id', $item->product->id))
                                                <a href="{{ route('cart.index') }}"
                                                    class="btn btn-warning text-uppercase fw-medium mb-3">
                                                    View Cart
                                                </a>
                                            @else
                                                <form name="addtocart-form" method="post"
                                                    action="{{ route('cart.add', ['id' => $item->product->id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->product->id }}">
                                                    <input type="hidden" name="price"
                                                        value="{{ $item->product->sale_price ?: $item->product->regular_price }}">
                                                    <button type="submit" class="btn btn-dark text-uppercase fw-medium"
                                                        title="Add To Cart">
                                                        Add To Cart
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('wishlist.remove', $item->id) }}">
                                                @csrf
                                                <a href="javascript:void(0)" class="remove-cart">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                        <path
                                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            <form method="POST" action="{{ route('wishlist.clear') }}">
                                @csrf
                                <button class="btn btn-light clear-cart" type="submit">CLEAR CART</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>No item found in your wishlist.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">Add Now</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        $('.remove-cart').on("click", function() {
            $(this).closest('form').submit();
        });

        $(function() {
            $('.clear-cart').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');

                swal({
                    title: "Clear Cart?",
                    text: "All items in your wishlist will be removed.\nThis action cannot be undone.",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            visible: true,
                            className: "swal-button--cancel"
                        },
                        confirm: {
                            text: "Delete",
                            className: "swal-button--danger"
                        }
                    },
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

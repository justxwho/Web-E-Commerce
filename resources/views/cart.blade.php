@extends('layouts.app')
@section('content')
    <style>
        .text-success {
            color: #278c04 !important;
        }

        .text-danger {
            color: #d61808 !important;
        }
    </style>
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
                @php
                    $items = $cart ? $cart->items : collect();
                    $items = $cart ? $cart->items : collect();
                    $subtotal = $items->sum(fn($i) => $i->price * $i->quantity);
                    $vat = $subtotal * 0.1;
                    $total = $subtotal + $vat;
                @endphp
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
                                        <td>
                                            <span class="shopping-cart__product-price">${{ $item->price }}</span>
                                        </td>
                                        <td>
                                            <div class="qty-control position-relative" data-id="{{ $item->id }}">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                    min="1" class="qty-control__number text-center" readonly>
                                                <div class="qty-control__reduce">-</div>
                                                <div class="qty-control__increase">+</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="shopping-cart__subtotal">${{ $item->price * $item->quantity }}</span>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
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
                            @if (!Session::has('coupon'))
                                <form action="{{ route('cart.coupon.apply') }}" method="POST"
                                    class="position-relative bg-body">
                                    @csrf
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code"
                                        value="">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="APPLY COUPON">
                                </form>
                            @else
                                <form action="{{ route('cart.coupon.remove') }}" method="POST"
                                    class="position-relative bg-body">
                                    @csrf
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code"
                                        value="@if (Session::has('coupon')) {{ Session::get('coupon')['code'] }} Applied! @endif">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="REMOVE COUPON">
                                </form>
                            @endif

                            <form method="POST" action="{{ route('cart.clear') }}">
                                <button class="btn btn-light clear-cart" type="submit">CLEAR CART</button>
                            </form>
                        </div>
                        <div>
                            @if (Session::has('success'))
                                <p class="text-success">{{ Session::get('success') }}</p>
                            @elseif (Session::has('error'))
                                <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Cart Totals</h3>
                                @if (Session::has('discounts'))
                                    @php $discounts = Session::get('discounts'); @endphp
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td id="cart-subtotal">${{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Discount ({{ Session::get('coupon')['code'] }})</th>
                                                <td id="cart-discount">- ${{ $discounts['discount'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal After Discount</th>
                                                <td id="cart-subtotal-after">${{ $discounts['subtotal'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td>Free Shipping</td>
                                            </tr>
                                            <tr>
                                                <th>VAT (10%)</th>
                                                <td id="cart-vat">${{ $discounts['tax'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td id="cart-total">${{ $discounts['total'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td id="cart-subtotal">${{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td>Free Shipping</td>
                                            </tr>
                                            <tr>
                                                <th>VAT (10%)</th>
                                                <td id="cart-vat">${{ number_format($vat, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td id="cart-total">${{ number_format($total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.qty-control').forEach(control => {

                let id = control.dataset.id;
                let input = control.querySelector('input');
                let decrease = control.querySelector('.qty-control__reduce');
                let increase = control.querySelector('.qty-control__increase');
                let row = control.closest('tr');
                let subtotalEl = row.querySelector('.shopping-cart__subtotal');

                function showLoading() {
                    let overlay = document.createElement('div');
                    overlay.className = 'qty-loading';
                    overlay.innerHTML = '<div class="qty-spinner"></div>';
                    control.appendChild(overlay);

                    control.style.pointerEvents = 'none';
                }

                function hideLoading() {
                    let overlay = control.querySelector('.qty-loading');
                    if (overlay) overlay.remove();

                    control.style.pointerEvents = 'auto';
                }

                function updateUI(data) {
                    input.value = data.qty;

                    subtotalEl.innerText = '$' + data.item_subtotal;
                    subtotalEl.classList.add('fade-update');

                    document.getElementById('cart-subtotal').innerText = '$' + data.cart_subtotal;
                    document.getElementById('cart-vat').innerText = '$' + data.vat.toFixed(2);
                    document.getElementById('cart-total').innerText = '$' + data.total.toFixed(2);

                    document.getElementById('cart-subtotal').classList.add('fade-update');
                    document.getElementById('cart-vat').classList.add('fade-update');
                    document.getElementById('cart-total').classList.add('fade-update');

                    setTimeout(() => {
                        subtotalEl.classList.remove('fade-update');
                        document.getElementById('cart-subtotal').classList.remove('fade-update');
                        document.getElementById('cart-vat').classList.remove('fade-update');
                        document.getElementById('cart-total').classList.remove('fade-update');
                    }, 250);
                }

                function handle(url) {
                    showLoading();

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            updateUI(data);
                            hideLoading();
                        })
                        .catch(err => {
                            console.log(err);
                            hideLoading();
                        });
                }

                increase.addEventListener('click', () => {
                    handle(`/cart/increase/${id}`);
                });

                decrease.addEventListener('click', () => {
                    handle(`/cart/decrease/${id}`);
                });

            });

        });

        $('.remove-cart').on("click", function() {
            $(this).closest('form').submit();
        });

        $(function() {
            $('.clear-cart').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');

                swal({
                    title: "Clear Cart?",
                    text: "All items in your cart will be removed.\nThis action cannot be undone.",
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

@push('styles')
    <style>
        .qty-loading {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            z-index: 10;
        }

        .qty-spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #ddd;
            border-top: 3px solid #ff6a00;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .fade-update {
            animation: fadeUp 0.25s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0.3;
                transform: translateY(3px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@extends('layouts.app')
@section('content')
    <main>
        <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow"
            data-settings='{
                "autoplay": {
                "delay": 5000
                },
                "slidesPerView": 1,
                "effect": "fade",
                "loop": true
            }'>
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="overflow-hidden position-relative h-100">
                            <div class="slideshow-character position-absolute bottom-0 pos_right-center">
                                <img loading="lazy" src="{{ asset('uploads/slides') }}/{{ $slide->image }}" width="542"
                                    height="733" alt="{{ $slide->title }}"
                                    class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
                                <div class="character_markup type2">
                                    <p
                                        class="text-uppercase font-sofia mark-grey-color animate animate_fade animate_btt animate_delay-10 mb-0">
                                        {{ $slide->tagline }}
                                    </p>
                                </div>
                            </div>
                            <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                                <h6
                                    class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                                    New Arrivals
                                </h6>
                                <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">
                                    {{ $slide->title }}
                                </h2>
                                <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">
                                    {{ $slide->subtitle }}
                                </h2>
                                <a href="{{ $slide->link }}"
                                    class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="container">
                <div
                    class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
                </div>
            </div>
        </section>
        <div class="container mw-1620 bg-white border-radius-10">
            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
            <section class="category-carousel container">
                <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">You Might Like</h2>

                <div class="position-relative">
                    <div class="swiper-container js-swiper-slider"
                        data-settings='{
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": 8,
                        "slidesPerGroup": 1,
                        "effect": "none",
                        "loop": true,
                        "navigation": {
                            "nextEl": ".products-carousel__next-1",
                            "prevEl": ".products-carousel__prev-1"
                        },
                        "breakpoints": {
                            "320": {
                            "slidesPerView": 2,
                            "slidesPerGroup": 2,
                            "spaceBetween": 15
                            },
                            "768": {
                            "slidesPerView": 4,
                            "slidesPerGroup": 4,
                            "spaceBetween": 30
                            },
                            "992": {
                            "slidesPerView": 6,
                            "slidesPerGroup": 1,
                            "spaceBetween": 45,
                            "pagination": false
                            },
                            "1200": {
                            "slidesPerView": 8,
                            "slidesPerGroup": 1,
                            "spaceBetween": 60,
                            "pagination": false
                            }
                        }
                        }'>
                        <div class="swiper-wrapper">
                            @foreach ($categories as $category)
                                <div class="swiper-slide">
                                    <img loading="lazy" class="mx-auto d-block h-auto mb-3"
                                        src="{{ asset('uploads/categories') }}/{{ $category->image }}" width="124"
                                        height="124" alt="" />
                                    <div class="text-center">
                                        <a href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                            class="menu-link fw-medium">{{ $category->name }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div><!-- /.swiper-wrapper -->
                    </div><!-- /.swiper-container js-swiper-slider -->

                    <div
                        class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_prev_md" />
                        </svg>
                    </div><!-- /.products-carousel__prev -->
                    <div
                        class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_next_md" />
                        </svg>
                    </div><!-- /.products-carousel__next -->
                </div><!-- /.position-relative -->
            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="hot-deals container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Hot Deals</h2>
                <div class="row">
                    <div
                        class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                        <h2>Summer Sale</h2>
                        <h2 class="fw-bold">Up to 60% Off</h2>

                        <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
                            data-date="18-3-2024" data-time="06:50">
                            <div class="day countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Days</span>
                            </div>

                            <div class="hour countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Hours</span>
                            </div>

                            <div class="min countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Mins</span>
                            </div>

                            <div class="sec countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Sec</span>
                            </div>
                        </div>

                        <a href="#" class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
                    </div>
                    <div class="col-md-6 col-lg-8 col-xl-80per">
                        <div class="position-relative">
                            <div class="swiper-container js-swiper-slider"
                                data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
                                <div class="swiper-wrapper">
                                    @foreach ($sproducts as $sproduct)
                                        <div class="swiper-slide product-card product-card_style3">
                                            <div class="pc__img-wrapper">
                                                <a
                                                    href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">
                                                    <img loading="lazy"
                                                        src="{{ asset('uploads/products') }}/{{ $sproduct->image }}"
                                                        width="258" height="313" alt="{{ $sproduct->name }}"
                                                        class="pc__img">

                                                </a>
                                            </div>

                                            <div class="pc__info position-relative">
                                                <h6 class="pc__title"><a
                                                        href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">{{ $sproduct->name }}</a>
                                                </h6>
                                                <div class="product-card__price d-flex">
                                                    <span class="money price text-secondary">
                                                        @if ($sproduct->sale_price)
                                                            <s>${{ $sproduct->regular_price }}</s>
                                                            ${{ $sproduct->sale_price }}
                                                        @else
                                                            ${{ $sproduct->regular_price }}
                                                        @endif
                                                    </span>
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach
                                </div><!-- /.swiper-wrapper -->
                            </div><!-- /.swiper-container js-swiper-slider -->
                        </div><!-- /.position-relative -->
                    </div>
                </div>
            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="category-banner container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="category-banner__item border-radius-10 mb-5">
                            <img loading="lazy" class="h-auto"
                                src="{{ asset('assets/images/home/demo3/category_9.jpg') }}" width="690"
                                height="665" alt="" />
                            <div class="category-banner__item-mark">
                                Starting at $19
                            </div>
                            <div class="category-banner__item-content">
                                <h3 class="mb-0">Blazers</h3>
                                <a href="#" class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="category-banner__item border-radius-10 mb-5">
                            <img loading="lazy" class="h-auto"
                                src="{{ asset('assets/images/home/demo3/category_10.jpg') }}" width="690"
                                height="665" alt="" />
                            <div class="category-banner__item-mark">
                                Starting at $19
                            </div>
                            <div class="category-banner__item-content">
                                <h3 class="mb-0">Sportswear</h3>
                                <a href="#" class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="products-grid container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Featured Products</h2>

                <div class="row">
                    @foreach ($fproducts as $fproduct)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                                <div class="pc__img-wrapper">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}">
                                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $fproduct->image }}"
                                            width="330" height="400" alt="Cropped Faux leather Jacket"
                                            class="pc__img">
                                    </a>
                                </div>

                                <div class="pc__info position-relative">
                                    <h6 class="pc__title"><a
                                            href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}">{{ $fproduct->name }}</a>
                                    </h6>
                                    <div class="product-card__price d-flex align-items-center">
                                        <span class="money price text-secondary">
                                            @if ($fproduct->sale_price)
                                                <s>${{ $fproduct->regular_price }}</s>
                                                ${{ $fproduct->sale_price }}
                                            @else
                                                ${{ $fproduct->regular_price }}
                                            @endif
                                        </span>
                                    </div>

                                    <div
                                        class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                        @if ($cart && $cart->items && $cart->items->where('product_id', $fproduct->id)->count() > 0)
                                            <a href="{{ route('cart.index') }}"
                                                class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn btn-warning mb-3">
                                                View Cart
                                            </a>
                                        @else
                                            <form name="addtocart-form" method="post"
                                                action="{{ route('cart.add', ['id' => $fproduct->id]) }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $fproduct->id }}">
                                                <input type="hidden" name="quantity" value="{{ $fproduct->quantity }}">
                                                <input type="hidden" name="name" value="{{ $fproduct->name }}">
                                                <input type="hidden" name="price"
                                                    value="{{ $fproduct->sale_price == '' ? $fproduct->regular_price : $fproduct->sale_price }}">
                                                <button
                                                    class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                                                    data-aside="cartDrawer" title="Add To Cart">
                                                    Add To Cart
                                                </button>
                                            </form>
                                        @endif
                                        <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                                            <span class="d-none d-xxl-block">Quick View</span>
                                            <span class="d-block d-xxl-none"><svg width="18" height="18"
                                                    viewBox="0 0 18 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_view" />
                                                </svg></span>
                                        </button>
                                        @php
                                            $inWishlist =
                                                $wishlistCart &&
                                                $wishlistCart->items->where('product_id', $fproduct->id)->count() > 0;
                                            $wishlistItem = $inWishlist
                                                ? $wishlistCart->items->where('product_id', $fproduct->id)->first()
                                                : null;
                                        @endphp
                                        @if (!$inWishlist)
                                            <form method="POST" action="{{ route('wishlist.add') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $fproduct->id }}">
                                                <input type="hidden" name="name" value="{{ $fproduct->name }}">
                                                <input type="hidden" name="price"
                                                    value="{{ $fproduct->sale_price == '' ? $fproduct->regular_price : $fproduct->sale_price }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist"
                                                    title="Add To Wishlist">
                                                    <svg width="16" height="16" viewBox="0 0 20 20"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_heart" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('wishlist.remove', $wishlistItem->id) }}">
                                                @csrf
                                                <button title="Remove From Wishlist" type="submit"
                                                    class="pc__btn-wl bg-transparent border-0 js-add-wishlist">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="#e74c3c">

                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                                                            2 5.42 4.42 3 7.5 3
                                                            c1.74 0 3.41.81 4.5 2.09
                                                            C13.09 3.81 14.76 3 16.5 3
                                                            19.58 3 22 5.42 22 8.5
                                                            c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div><!-- /.row -->

                <div class="text-center mt-2">
                    <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="#">Load
                        More</a>
                </div>
            </section>
        </div>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    </main>
@endsection

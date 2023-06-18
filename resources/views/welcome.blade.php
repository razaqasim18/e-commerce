@extends('layouts.eshop')
@section('style')
    <!-- Glidejs css files -->
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.core.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.theme.min.css') }}" />

    <style>
        .colstyle {
            margin: 1%;
            border-radius: 4px;
            padding: 5%;
            box-shadow: 0px 0px 6px 0px {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
        }
    </style>
@endsection

@section('content')
    @if (count($banner))
        <!-- Slider Area -->
        <section class="hero-slider" style="height: 350px;">
            <!-- Single Slider -->
            <div class="single-slider">
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @foreach ($banner as $image)
                                <li class="glide__slide">
                                    <img src="{{ $image->getFirstMediaUrl('images') }}" alt="{{ $image->name }}" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ End Single Slider -->
        </section>
        <!--/ End Slider Area -->
    @else
        <!--else Slider Area -->
        <section class="hero-slider" style="height: 350px;">
            <!-- Single Slider -->
            <div class="single-slider forcustom" style="height: 350px;">
                <div class="container">
                    <div class="row no-gutters">
                        <div class="col-lg-9 offset-lg-3 col-12">
                            <div class="text-inner">
                                {{--  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Single Slider -->
        </section>
        <!--else End Slider Area -->
    @endif

    <!-- Start Small Banner  -->
    <section class="small-banner section">
        <div class="container-fluid">
            <div class="row">
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{ asset('eshop/images/cream1.jpeg') }}" alt="#">
                        <div class="content">
                        </div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <img src="{{ asset('eshop/images/cream2.jpeg') }}" alt="#">
                        <div class="content">
                        </div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-12">
                    <div class="single-banner tab-height">
                        <img src="{{ asset('eshop/images/cream3.jpeg') }}" alt="#">
                        <div class="content">
                        </div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
            </div>
        </div>
    </section>
    <!-- End Small Banner -->

    <!-- Start Feature Popular -->
    <div class="product-area most-popular section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Feature Product</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach ($featureproduct as $row)
                            <!-- Start Single Product -->
                            <div class="single-product colstyle">
                                <div class="product-img">
                                    <a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">
                                        <img class="default-img"
                                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                            alt="{{ $row->product }}">
                                        <img class="hover-img"
                                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                            alt="{{ $row->product }}">
                                        @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                            <span class="out-of-stock">Out of stock</span>
                                        @endif
                                    </a>
                                    <div class="button-head">
                                        <div class="product-action">
                                            <a class="viewProduct" title="Quick View"
                                                href="{{ route('product.detail', Crypt::encrypt($row->id)) }}"><i
                                                    class=" ti-eye"></i><span>Quick Shop</span></a>
                                        </div>
                                        <div class="product-action-2">
                                            @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                <a href="javascript:void(0)">Out of stock</a>
                                            @else
                                                <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                                    data-productid="{{ $row->id }}">Add to cart</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <h3><a
                                            href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                                    </h3>
                                    <div class="product-price">
                                        {{-- <span class="old">$60.00</span> --}}
                                        <span>{{ 'PKR ' . $row->price }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Feature Popular -->

    <!-- Start Product Area -->
    <div class="product-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>New Arrival</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                        <div class="row">
                            @foreach ($newproduct as $row)
                                <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                    <div class="single-product colstyle">
                                        <div class="product-img">
                                            <a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">
                                                <img class="default-img"
                                                    src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                                    alt="{{ $row->product }}">
                                                <img class="hover-img"
                                                    src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                                    alt="{{ $row->product }}">
                                                @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                    <span class="out-of-stock">Out of stock</span>
                                                @endif
                                            </a>
                                            <div class="button-head">
                                                <div class="product-action">
                                                    <a class="viewProduct" title="Quick View"
                                                        href="{{ route('product.detail', Crypt::encrypt($row->id)) }}"><i
                                                            class=" ti-eye"></i><span>Quick Shop</span></a>
                                                </div>
                                                <div class="product-action-2">
                                                    @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                        <a href="javascript:void(0)">Out of stock</a>
                                                    @else
                                                        <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                                            data-productid="{{ $row->id }}">Add to cart</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3><a
                                                    href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                                            </h3>
                                            <div class="product-price">
                                                <span>{{ 'PKR ' . $row->price }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Product Area -->

    <!-- our Testimonials start -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Our Testimonials</h2>
                    </div>
                </div>
            </div>
            <div>
                <div class="containerOwl rounded">
                    <div class="owl-carousel owl-theme">
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Good Service
                                </div>
                                <div class="testimonial">
                                    Lorem ipsum, dolor sit amet consectetur
                                    adipisicing elit. Magni dolores molestias veniam
                                    inventore itaque eius iure omnis, temporibus culpa
                                    id.
                                </div>
                                <div class="d-flex flex-row profile pt-4 mt-auto">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Megan</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Great Service
                                </div>
                                <div class="testimonial">
                                    Lorem ipsum, dolor sit amet consectetur
                                    adipisicing elit. Magni dolores molestias veniam
                                    inventore itaque eius iure omnis, temporibus culpa
                                    id.
                                </div>
                                <div class="d-flex flex-row profile pt-4 mt-auto">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Olivia</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Great Quality
                                </div>
                                <div class="testimonial">
                                    Lorem ipsum, dolor sit amet consectetur
                                    adipisicing elit. Magni dolores molestias veniam
                                    inventore itaque eius iure omnis, temporibus culpa
                                    id.
                                </div>
                                <div class="d-flex flex-row profile pt-4 mt-auto">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Jessica</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Good Service
                                </div>
                                <div class="testimonial">
                                    Lorem ipsum, dolor sit amet consectetur
                                    adipisicing elit. Magni dolores molestias veniam
                                    inventore itaque eius iure omnis, temporibus culpa
                                    id.
                                </div>
                                <div class="d-flex flex-row profile pt-4 mt-auto">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Jeoana</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Amazing Products
                                </div>
                                <div class="testimonial">
                                    Lorem ipsum, dolor sit amet consectetur
                                    adipisicing elit. Magni dolores molestias veniam
                                    inventore itaque eius iure omnis, temporibus culpa
                                    id.
                                </div>
                                <div class="d-flex flex-row profile pt-4 mt-auto">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Emily</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- our Testimonials ends -->



@endsection

@section('script')
    <script src="{{ asset('eshop/js/glidejs/glide.min.js') }}"></script>
    <script>
        var glideHero = new Glide('.glide', {
            type: 'carousel',
            animationDuration: 2000,
            autoplay: 3000,
            focusAt: '1',
            startAt: 1,
            perView: 1,
            loop: true,
        });
        glideHero.mount();
    </script>

    <!-- Owl carousol code -->
    <script>
        $(document).ready(function() {
            var silder = $('.owl-carousel');
            silder.owlCarousel({
                autoPlay: false,
                items: 1,
                center: false,
                nav: true,
                margin: 30,
                dots: false,
                loop: true,
                navText: [
                    "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
                    "<i class='fa fa-arrow-right' aria-hidden='true'></i>",
                ],
                responsive: {
                    0: {
                        items: 1,
                    },
                    575: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    991: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    },
                },
            });
        });
    </script>
@endsection

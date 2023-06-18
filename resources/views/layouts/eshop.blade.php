<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content=''>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title Tag  -->
    <title>{{ SettingHelper::getSettingValueBySLug('site_name') }}</title>
    <!-- Favicon -->
    <link rel='shortcut icon' type='image/x-icon'
        href=' {{ SettingHelper::getSettingValueBySLug('site_favicon') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_favicon')) : asset('img/favicon.ico') }}' />
    <!-- Web Font -->
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <!-- StyleSheet -->
    @yield('style')

    <!-- FontAwesome StyleSheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('eshop/css/bootstrap.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('eshop/css/magnific-popup.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('eshop/css/font-awesome.css') }}">
    <!-- Fancybox -->
    <link rel="stylesheet" href="{{ asset('eshop/css/jquery.fancybox.min.css') }}">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="{{ asset('eshop/css/themify-icons.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('eshop/css/niceselect.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('eshop/css/animate.css') }}">
    <!-- Flex Slider CSS -->
    <link rel="stylesheet" href="{{ asset('eshop/css/flex-slider.min.css') }}">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('eshop/css/owl-carousel.css') }}">
    <!-- Slicknav -->
    <link rel="stylesheet" href="{{ asset('eshop/css/slicknav.min.css') }}">

    <!-- Eshop StyleSheet -->
    <link rel="stylesheet" href="{{ asset('eshop/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('eshop/style.css') }}">
    <link rel="stylesheet" href="{{ asset('eshop/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/izitoast/css/iziToast.min.css') }}">

    @includeIf('include.eshop_style')
</head>

<body class="js">

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- End Preloader -->


    <!-- Header -->
    <header class="header shop">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-12">
                        <!-- Top Left -->
                        <div class="top-left">
                            <ul class="list-main">
                                <li><i class="ti-headphone-alt"></i>{{ env('APP_PHONE') }}</li>
                                <li><i class="ti-email"></i>{{ env('MAIL_FROM_ADDRESS') }}</li>
                            </ul>
                        </div>
                        <!--/ End Top Left -->
                    </div>
                    <div class="col-lg-8 col-md-12 col-12">
                        <!-- Top Right -->
                        <div class="right-content">
                            <ul class="list-main">
                                {{-- <li><i class="ti-location-pin"></i> Store location</li> --}}
                                {{-- <li><i class="ti-alarm-clock"></i> <a href="#">Daily deal</a></li> --}}
                                @if (Auth::guard('web')->user())
                                    <li><i class="ti-user"></i> <a href="{{ route('dashboard') }}">My account</a></li>
                                @else
                                    <li>
                                        <i class="ti-pin"></i>
                                        <a href="{{ route('request.epin.load') }}">RG Code</a>
                                    </li>
                                    <li><i class="ti-user"></i><a href="{{ route('register') }}">Register</a></li>
                                    <li><i class="ti-power-off"></i><a href="{{ route('login') }}">Login</a></li>
                                @endif
                            </ul>
                        </div>
                        <!-- End Top Right -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->
        <div class="middle-inner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-12">
                        <!-- Logo -->
                        <div class="logo">
                            <a href="{{ route('welcome') }}"><img
                                    src="{{ SettingHelper::getSettingValueBySLug('site_logo') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_logo')) : asset('img/logo.png') }}"
                                    alt="logo"></a>
                        </div>
                        <!--/ End Logo -->

                        <div class="mobile-nav"></div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-12">

                    </div>
                    <div class="col-lg-2 col-md-3 col-12">
                        <div class="right-bar">
                            <div class="sinlge-bar">
                                <a href="#" class="single-icon"><i class="fa fa-user-circle-o"
                                        aria-hidden="true"></i></a>
                            </div>
                            <div class="sinlge-bar shopping">
                                <a href="#" class="single-icon">
                                    <i class="ti-bag"></i>
                                    <span class="total-count spanItemCount"></span>
                                </a>
                                <!-- Shopping Item -->
                                <div class="shopping-item">
                                    <div class="dropdown-cart-header">
                                        <span> <span class="spanItemCount"></span> Items</span>
                                        <a href="{{ route('cart.index') }}">View Cart</a>
                                    </div>
                                    <ul class="shopping-list" id="shoppingList">

                                    </ul>
                                    <div class="bottom">
                                        <div class="total">
                                            <span>Total</span>
                                            <span class="total-amount" id="totalAmount"></span>
                                        </div>
                                        <a href="{{ route('checkout') }}" class="btn animate"
                                            @if (Auth::guard('web')->user()) disabled @endif>Checkout</a>
                                    </div>
                                </div>
                                <!--/ End Shopping Item -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Inner -->
        <div class="header-inner">
            <div class="container">
                <div class="cat-nav-head">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="all-category">
                                <h3 class="cat-heading">{{ SettingHelper::getSettingValueBySLug('site_name') }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-9 col-12">
                            <div class="menu-area">
                                <!-- Main Menu -->
                                <nav class="navbar navbar-expand-lg">
                                    <div class="navbar-collapse">
                                        <div class="nav-inner">
                                            <ul class="nav main-menu menu navbar-nav">
                                                <li @if (Route::current()->getName() == 'welcome') class="active" @endif><a
                                                        href="{{ route('welcome') }}">Home</a></li>
                                                <li @if (Route::current()->getName() == 'shop') class="active" @endif>
                                                    <a href="#">Shop
                                                    </a>
                                                    <ul class="dropdown">
                                                        <li><a href="{{ route('shop') }}">Shop</a></li>
                                                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                                                        @if (Auth::guard('web')->user())
                                                            <li><a href="{{ route('checkout') }}">Checkout</a></li>
                                                        @endif
                                                    </ul>
                                                </li>
                                                <li @if (Route::current()->getName() == 'other.brand') class="active" @endif><a
                                                        href="{{ route('other.brand') }}">Other Brand</a></li>
                                                <li @if (Route::current()->getName() == 'blogs') class="active" @endif><a
                                                        href="{{ route('blogs') }}">Blogs</a></li>
                                                <li @if (Route::current()->getName() == 'contact.us') class="active" @endif><a
                                                        href="{{ route('contact.us') }}">Contact Us</a></li>
                                                <li @if (Route::current()->getName() == 'about.us') class="active" @endif><a
                                                        href="{{ route('about.us') }}">About Us</a></li>
                                                <li @if (Route::current()->getName() == 'success.stories') class="active" @endif><a
                                                        href="{{ route('success.stories') }}">Leaders Stories</a></li>
                                                @if (SettingHelper::getSettingValueBySLug('catalog'))
                                                    <li>
                                                        <a download
                                                            href="{{ asset('uploads/catalog') . '/' . SettingHelper::getSettingValueBySLug('catalog') }}">Catalog</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                                <!--/ End Main Menu -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>
    <!--/ End Header -->

    @yield('content')


    <section class="shop-services section home" style="margin-top:100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Free shiping</h4>
                        <p>Orders over $100</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Free Return</h4>
                        <p>Within 30 days returns</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Sucure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Best Peice</h4>
                        <p>Guaranteed price</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>

    <!-- Start Shop Newsletter  -->
    <section class="shop-newsletter section">
        <div class="container">
            <div class="inner-top">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="inner">
                            <h4 class="text-left">Follow us</h4>
                            <p class="text-left mb-4">
                                Stay connected with us on social media for business
                                updates!
                            </p>
                        </div>
                        <div class="d-flex contaact_us_icons">
                            <i class="ti-facebook"></i>
                            <i class="ti-instagram"></i>
                            <i class="ti-youtube"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Newsletter -->

    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Footer Top -->
        <div class="footer-top section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer about">
                            <div class="logo">
                                <a href="{{ route('welcome') }}"><img
                                        src="{{ SettingHelper::getSettingValueBySLug('site_logo') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_logo')) : asset('img/logo.png') }}"
                                        alt="#"></a>
                            </div>
                            <p class="text">Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue,
                                magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan
                                porttitor, facilisis luctus, metus.</p>
                            <p class="call">Got Question? Call us 24/7
                                <span>
                                    <a href="tel:{{ env('APP_PHONE') }}">{{ env('APP_PHONE') }}</a>
                                </span>
                            </p>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class="col-lg-2 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer links">
                            <h4>Information</h4>
                            <ul>
                                <li><a href="{{ route('about.us') }}">About Us</a></li>
                                <li><a href="{{ route('terms.condition') }}">Terms & Conditions</a></li>
                                <li><a href="{{ route('contact.us') }}">Contact Us</a></li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class="col-lg-2 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer links">
                            <h4>Customer Service</h4>
                            <ul>
                                {{-- <li><a href="#">Payment Methods</a></li>
                                <li><a href="#">Money-back</a></li>
                                <li><a href="#">Returns</a></li>
                                <li><a href="#">Shipping</a></li> --}}
                                <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer social">
                            <h4>Get In Tuch</h4>
                            <!-- Single Widget -->
                            <div class="contact">
                                <ul>
                                    <li>NO. 342 - London Oxford Street.</li>
                                    <li>012 United Kingdom.</li>
                                    <li>{{ env('MAIL_FROM_ADDRESS') }}</li>
                                    <li>{{ env('APP_PHONE') }}</li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                            <ul>
                                <li><a href="#"><i class="ti-facebook"></i></a></li>
                                <li><a href="#"><i class="ti-twitter"></i></a></li>
                                <li><a href="#"><i class="ti-flickr"></i></a></li>
                                <li><a href="#"><i class="ti-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Top -->
        <div class="copyright">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="left">
                                <p>Copyright © 2020 <a href="https://trylotech.com/" target="_blank">Trylo Tech</a> -
                                    All Rights Reserved.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="right">
                                <img src="{{ asset('eshop/images/payments.png') }}" alt="#"
                                    style="height: auto;max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- /End Footer Area -->

    <!-- Jquery -->
    <script src="{{ asset('eshop/js/jquery.min.js') }}"></script>
    <script src="{{ asset('eshop/js/jquery-migrate-3.0.0.js') }}"></script>
    <script src="{{ asset('eshop/js/jquery-ui.min.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('eshop/js/popper.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('eshop/js/bootstrap.min.js') }}"></script>
    <!-- Color JS -->
    {{-- <script src="{{ asset('eshop/js/colors.js') }}"></script> --}}
    <!-- Slicknav JS -->
    <script src="{{ asset('eshop/js/slicknav.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset('eshop/js/owl-carousel.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('eshop/js/magnific-popup.js') }}"></script>
    <!-- Waypoints JS -->
    <script src="{{ asset('eshop/js/waypoints.min.js') }}"></script>
    <!-- Countdown JS -->
    <script src="{{ asset('eshop/js/finalcountdown.min.js') }}"></script>
    <!-- Nice Select JS -->
    <script src="{{ asset('eshop/js/nicesellect.js') }}"></script>
    <!-- Flex Slider JS -->
    <script src="{{ asset('eshop/js/flex-slider.js') }}"></script>
    <!-- ScrollUp JS -->
    <script src="{{ asset('eshop/js/scrollup.js') }}"></script>
    <!-- Onepage Nav JS -->
    <script src="{{ asset('eshop/js/onepage-nav.min.js') }}"></script>
    <!-- Easing JS -->
    <script src="{{ asset('eshop/js/easing.js') }}"></script>
    <!-- Active JS -->
    <script src="{{ asset('eshop/js/active.js') }}"></script>
    <script src="{{ asset('bundles/izitoast/js/iziToast.min.js') }}"></script>

    <script>
        function refreshCSRFToken() {
            $.ajax({
                url: '{{ route('refresh-csrf-token') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Update the CSRF token value in the meta tag
                    $('meta[name="csrf-token"]').attr('content', response.csrf_token);

                    // Update the CSRF token value in the AJAX headers
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': response.csrf_token
                        }
                    });
                }
            });
        }

        $.ajaxSetup({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });
    </script>
    @yield('script')
    <script>
        $('document').ready(function() {
            getItemList();

            $('body').on("click", "a#addToCart", function() {
                let productid = $(this).data('productid');
                let productquantity = 1;
                if ($("input#quantity").val()) {
                    productquantity = $("input#quantity").val();
                }
                refreshCSRFToken();
                $.ajax({
                    url: '{{ route('cart.insert') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        productid: productid,
                        quantity: productquantity
                    },
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(response) {
                        if (response.type) {
                            iziToast.success({
                                title: 'Success',
                                message: response.msg,
                                position: 'topRight'
                            });
                            getItemList();
                        } else {
                            iziToast.error({
                                title: 'Error!',
                                message: response.msg,
                                position: 'topRight'
                            });
                        }
                    }
                });
            });

            $('body').on("click", "a.removeProduct", function() {
                let productid = $(this).data('productid');
                let isdiscount = $(this).data('isdiscount');
                refreshCSRFToken();
                $.ajax({
                    url: '{{ route('cart.delete') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        productid: productid,
                        isdiscount: isdiscount,
                    },
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(response) {
                        if (response.type) {
                            iziToast.success({
                                title: 'Success',
                                message: response.msg,
                                position: 'topRight'
                            });
                            if (window.location.toString().includes("cart")) {
                                location.reload();
                            } else {
                                getItemList();
                            }
                        } else {
                            iziToast.error({
                                title: 'Error!',
                                message: response.msg,
                                position: 'topRight'
                            });
                        }
                    }
                });
            });


        });

        function getItemList() {
            $.ajax({
                url: '{{ route('cart.list') }}',
                method: "GET",
                // data: {
                //     _token: '{{ csrf_token() }}',
                //     productid: productid,
                // },
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").hide();
                },
                success: function(response) {
                    var item = '';
                    if (Object.keys(response.list_normal).length) {
                        Object.keys(response.list_normal).forEach(function(key) {
                            const element = response.list_normal[key];
                            element.attributes['product_image'];
                            var image = element.attributes.product_image ?
                                "{{ asset('uploads/product') }}" + '/' + element.attributes
                                .product_image : "{{ asset('img/products/product-1.png') }}";
                            // console.log(key, response.list[key]);
                            item +=
                                '<li><a href="javascript:void(0)" class="remove removeProduct" data-productid="' +
                                element.id +
                                '" data-isdiscount="0" title="Remove this item"> <i class="fa fa-remove"> </i></a>';
                            item +=
                                '<a class="cart-img" href="javascript:void(0)"><img src="' + image +
                                '" alt="#"></a>';
                            item += '<h4><a href="javascript:void(0)">' + element.name + '</a></h4>';
                            item +=
                                '<p class = "quantity">' + element.quantity +
                                'x - <span class="amount"> PKR ' + element.price + '</span></p></li>';
                            // }
                        });
                    }
                    if (Object.keys(response.list_discount).length) {
                        Object.keys(response.list_discount).forEach(function(key) {
                            const element = response.list_discount[key];
                            element.attributes['product_image'];
                            var image = element.attributes.product_image ?
                                "{{ asset('uploads/product') }}" + '/' + element.attributes
                                .product_image : "{{ asset('img/products/product-1.png') }}";
                            // console.log(key, response.list[key]);
                            item +=
                                '<li><a href="javascript:void(0)" class="remove removeProduct" data-productid="' +
                                element.id +
                                '" data-isdiscount="1" title="Remove this item"> <i class="fa fa-remove"> </i></a>';
                            item +=
                                '<a class="cart-img" href="javascript:void(0)"><img src="' + image +
                                '" alt="#"></a>';
                            item += '<h4><a href="javascript:void(0)">' + element.name + '</a></h4>';
                            item +=
                                '<p class = "quantity">' + element.quantity +
                                'x - <span class="amount"> PKR ' + element.price + '</span></p></li>';
                            // }
                        });
                    }
                    $("ul#shoppingList").html(item);
                    $("span.spanItemCount").text(response.count ? response.count : 0);
                    $("#totalAmount").text("PKR " + response.subtotal);
                }
            });
        }
    </script>
</body>

</html>

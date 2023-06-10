@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <section class="small-banner section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <!-- Product Slider -->
                    <div class="product-gallery">
                        <div class="quickview-slider-active">
                            @if ($product->image)
                                <div class="single-slider">
                                    <img src="{{ asset('uploads/product') . '/' . $product->image }}"
                                        alt="{{ $product->product }}">
                                </div>
                            @endif
                            @if ($product->getMedia('images'))
                                @foreach ($product->getMedia('images') as $image)
                                    <div class="single-slider">
                                        <img src="{{ $image->getUrl() }}" alt="{{ $image->name }}">
                                    </div>
                                @endforeach
                            @endif
                            @if (!($product->image && $product->getMedia('images')))
                                <div class="single-slider">
                                    <img src="{{ asset('img/products/product-1.png') }}" alt="{{ $product->image }}">
                                </div>
                                <div class="single-slider">
                                    <img src="{{ asset('img/products/product-1.png') }}" alt="{{ $product->image }}">
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- End Product slider -->
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="quickview-content">
                        <h2>{{ $product->product }}</h2>

                        <h3>
                            <a class="midium-banner single-banner a">PRICE</a>: {{ 'PKR ' . $product->price }}
                        </h3>
                        <h3>
                            <a class="midium-banner single-banner a">IN Stock</a>:
                            @if ($product->in_stock == 0 || $product->in_stock <= 0)
                                Not Available
                            @else
                                Available
                            @endif
                        </h3>

                        <div class="quickview-peragraph">
                            <p>{{ $product->description }}</p>
                        </div>
                        <br />

                        @if ($product->in_stock == 0 || $product->in_stock <= 0)
                            <div class="add-to-cart">
                                <a href="javascript:void(0)" class="btn btn-danger" style="cursor: not-allowed;">Out of
                                    stock</a>
                            </div>
                        @else
                            @if (!\Cart::session('discount')->get($product->id))
                                @if ($product->is_discount || $product->discount > 0)
                                    <div class="add-to-cart-discount text-center">
                                        <a href="javascript:void(0)" id="addToCartDiscount"
                                            data-productid="{{ $product->id }}" class="btn btn-block">Add to cart with
                                            {{ $product->discount }} %</a>
                                    </div>
                                @endif
                            @endif

                            <br /> <br />

                            <div class="quantity">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                            data-type="minus" data-field="quant[1]">
                                            <i class="ti-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" id="quantity" data-productid="{{ $product->id }}"
                                        name="quant[1]" class="input-number" data-min="1" data-max="1000" value="1">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                            data-field="quant[1]">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </div>
                            <div class="add-to-cart">
                                <a href="javascript:void(0)" id="addToCart" data-productid="{{ $product->id }}"
                                    class="btn">Add to cart</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('a#addToCartDiscount').on('click', function() {
            let productid = $(this).data('productid');
            let productquantity = 1;
            if ($("input#quantity").val()) {
                productquantity = $("input#quantity").val();
            }
            $.ajax({
                url: "{{ route('cart.insert.discount') }}",
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
                        $('a#addToCartDiscount').remove();
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
    </script>
@endsection

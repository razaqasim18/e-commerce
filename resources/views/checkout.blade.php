@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <form class="form" method="post" action="{{ route('checkout.process') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>

                    <div class="col-lg-8 col-12">
                        @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                            <div class="checkout-form">
                                <h2>Make Your Checkout Here</h2>
                                <p>Where You want us to deliver </p>
                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label>First Name<span>*</span></label>
                                            <input type="text" name="name" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->name }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email Address<span>*</span></label>
                                            <input type="email" name="email" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->email }}">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Phone Number<span>*</span></label>
                                            <input type="number" name="phone" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->phone }}">
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>City<span>*</span></label>
                                            <select class="city" name="city" required>
                                                <option value="">Select Option</option>
                                                @foreach ($city as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if (Auth::guard('web')->user()->userdetail->city_id == $row->id) selected @endif>
                                                        {{ $row->city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Street<span>*</span></label>
                                            <input type="text" name="street" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->street }}">
                                            @error('street')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Address<span>*</span></label>
                                            <input type="text" name="address" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->address }}">
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Shipping Address</label>
                                            <input type="text" name="shipping_address" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->shipping_address }}">
                                            @error('shipping_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label>Other Information</label>
                                            <input type="text" name="other" placeholder="">
                                            @error('other')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!--/ End Form -->
                            </div>
                        @else
                            <div class="checkout-form">
                                <p>Please add your shipping details for checkout
                                </p>
                                <a class="text-primary" href="{{ route('profile.load') }}">Profile Information</a>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="order-details">
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>CART TOTALS</h2>
                                <div class="content">
                                    @php
                                        $attribute = $weight = $subtotal = 0;
                                        foreach (\Cart::session('normal')->getContent() as $item) {
                                            $attribute = $attribute + $item->attributes->product_points;
                                            $weight = $weight + $item->attributes->product_weight;
                                            $subtotal = $subtotal + $item->getPriceSum();
                                        }
                                        foreach (\Cart::session('discount')->getContent() as $item) {
                                            $attribute = $attribute + $item->attributes->product_points;
                                            $weight = $weight + $item->attributes->product_weight;
                                            $subtotal = $subtotal + $item->getPriceSum();
                                        }
                                        $shippingcharges = $weight * SettingHelper::getSettingValueBySLug('shipping_charges');
                                        if (Session::get('coupon_discount') > 0) {
                                            $discount = $subtotal * (Session::get('coupon_discount') / 100);
                                        } else {
                                            $discount = 0;
                                        }
                                        $totalpay = $subtotal - $discount + $shippingcharges;
                                    @endphp
                                    <ul>
                                        <li>
                                            Sale Point<span>{{ $attribute }}</span>
                                            <input type="hidden" name="subpoint" id="subpoint"
                                                value="{{ $attribute }}" />
                                        </li>
                                        <li>
                                            Weight <span>{{ $weight }} KG</span>
                                            <input type="hidden" name="totalweight" id="totalweight"
                                                value="{{ $weight }}" />
                                        </li>
                                        <li>
                                            Sub Total<span>PKR {{ $subtotal }}</span>
                                            <input type="hidden" name="subtotal" id="subtotal"
                                                value="{{ $subtotal }}" />
                                        </li>
                                        <li>
                                            (-) Discount
                                            <span>PKR {{ $discount }}</span>
                                            <input type="hidden" name="discount" id="discount"
                                                value="{{ $discount }}" />
                                        </li>
                                        <li>
                                            (+) Shipping
                                            <span>PKR {{ $shippingcharges }}</span>
                                            <input type="hidden" name="shippingcharges" id="totalshippingcharges"
                                                value="{{ $shippingcharges }}" />
                                        </li>
                                        <li class="last">
                                            Total<span>PKR {{ $totalpay }}</span>
                                            <input type="hidden" name="totalpay" id="totalpay"
                                                value="{{ $totalpay }}" />
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <!--/ End Order Widget -->
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>Payments</h2>
                                <div class="content">
                                    <div style="display:block;padding: 10px 27px;">
                                        <input name="payment_by" id="payment_cash" type="radio" value="0"
                                            checked>
                                        <label for="payment_cash">
                                            By Cash
                                        </label><br />
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <input name="payment_by" id="payment_wallet" type="radio"
                                                    value="1">
                                                <label for="payment_wallet">
                                                    By Wallet
                                                </label>
                                            </div>
                                            @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                                                <div class="col-md-6 text-right">
                                                    <label><b>Balance:</b> {!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!} </label>
                                                </div>
                                                <input type="hidden" name="balance" value="{!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!}" />
                                                @error('balance')
                                                    <span class="col-md-12 text-danger">{{ $message }}</span>
                                                @enderror
                                            @endif

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <input name="payment_by" id="payment_wallet" type="radio"
                                                    value="2">
                                                <label for="payment_wallet">
                                                    By Reward
                                                </label>
                                            </div>
                                            @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                                                <div class="col-md-6 text-right">
                                                    <label><b>Balance:</b> {!! CustomHelper::getUserWalletGiftByid(Auth::user()->id) !!} </label>
                                                </div>
                                                <input type="hidden" name="giftbalance"
                                                    value="{!! CustomHelper::getUserWalletGiftByid(Auth::user()->id) !!}" />
                                                @error('giftbalance')
                                                    <span class="col-md-12 text-danger">{{ $message }}</span>
                                                @enderror
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--/ End Order Widget -->

                            <!-- Button Widget -->
                            <div class="single-widget get-button">
                                <div class="content">
                                    <div class="button">
                                        <button type="submit" class="btn"
                                            @if (
                                                !(Auth::guard('web')->user() &&
                                                    Auth::guard('web')->user()->userdetail &&
                                                    (\Cart::session('normal')->getContent()->count() ||
                                                        \Cart::session('discount')->getContent()->count())
                                                )) disabled @endif>proceed to checkout</button>
                                    </div>
                                </div>
                            </div>
                            <!--/ End Button Widget -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->
@endsection
@section('script')
    <script></script>
@endsection

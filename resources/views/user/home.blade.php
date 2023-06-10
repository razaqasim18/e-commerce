@extends('layouts.user')
@section('title')
    User || Dasboard
@endsection
@section('style')
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center" style="display:block; padding:6px; color: #212529;">
                                        <h6>Pending Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['pending']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;  color: #212529;">
                                        <h6>Inprocess Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['inprocess']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529;">
                                        <h6>Approved Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['approved']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529;">
                                        <h6>Delivered Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['delivered']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Reject Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['cancelled']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529; color: #212529;">
                                        <h4>Total Orders</h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['total']->count }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card card-primary author-box">
                            <div class="card-body">
                                <div class="author-box-center">
                                    <img alt="image"
                                        src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"
                                        class="rounded-circle author-box-picture">
                                    <div class="clearfix"></div>
                                    <div class="author-box-name">
                                        <h4 class="mt-2">{{ Auth::guard('web')->user()->name }}</h4>
                                    </div>
                                    <div class="author-box-job">
                                        {{ Auth::guard('web')->user()->userpoint ? (Auth::guard('web')->user()->userpoint->commission ? Auth::guard('web')->user()->userpoint->commission->title : '') : '' }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="author-box-description">
                                        <p class="mb-0">
                                            <strong>ID: </strong>
                                            {{ 'ABF-' . Auth::guard('web')->user()->id }}
                                        </p>
                                    </div>
                                    <div class="w-100 d-sm-none"></div>
                                </div>
                                <div class="py-2">
                                    <p class="clearfix mb-1">
                                        <span class="float-left">
                                            Birthday
                                        </span>
                                        <span class="float-right text-muted">
                                            {{ date('d M Y', strtotime(Auth::guard('web')->user()->dob)) }}
                                        </span>
                                    </p>
                                    <p class="clearfix mb-1">
                                        <span class="float-left">
                                            Phone
                                        </span>
                                        <span class="float-right text-muted">
                                            {{ Auth::guard('web')->user()->phone }}
                                        </span>
                                    </p>
                                    <p class="clearfix mb-1">
                                        <span class="float-left">
                                            Mail
                                        </span>
                                        <span class="float-right text-muted">
                                            {{ Auth::guard('web')->user()->email }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Total Sale Point</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> {{ $user['point'] ? $user['point']->point : 0 }} </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Personal Sale Point</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> {{ $user['personalpoint'] ? $user['personalpoint']->count : 0 }} </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Wallet</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> Rs: {{ $user['wallet'] ? $user['wallet']->amount : 0 }} </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Reward</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> Rs: {{ $user['wallet'] ? $user['wallet']->gift : 0 }} </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Total Earning</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> Rs: {{ $user['wallet'] ? $user['wallet']->amount : 0 }} </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 text-center">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h4>Monthly Earning</h4>
                                    </div>
                                    <div class="card-body p-1">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3> Rs:
                                                    {{ $user['personalmonthlyearning']->count ? $user['personalmonthlyearning']->count : 0 }}
                                                </h3>
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection

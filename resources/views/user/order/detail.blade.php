@extends('layouts.user')
@section('title')
    Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <h2>Invoice</h2>
                                    <div class="invoice-number">Order #{{ $order->order_no }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Billed To:</strong><br>
                                            {{ $order->orderShippingDetail->name }}<br>
                                            {{ $order->orderShippingDetail->email }},<br>
                                            {{ $order->orderShippingDetail->phone }}<br>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Shipped To:</strong><br>
                                            {{ $order->orderShippingDetail->address }}<br>
                                            {{ $order->orderShippingDetail->street }}<br>
                                            {{ $order->orderShippingDetail->city->city }}<br>
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Payment Method:</strong><br>
                                            @if ($order->payment_by == 1)
                                                Wallet
                                            @elseif ($order->payment_by == 2)
                                                Cash Back
                                            @else
                                                Cash
                                            @endif
                                            <br>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Order Date:</strong><br>
                                            {{ date('d M Y', strtotime($order->created_at)) }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title">Order Summary</div>
                                <p class="section-lead">All items here cannot be deleted.</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th>Item</th>
                                            <th class="text-center">Point</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Totals</th>
                                        </tr>
                                        @php $i = 1; @endphp
                                        @foreach ($order->orderDetail as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row->product }}</td>
                                                <td class="text-center">{{ $row->points }}</td>
                                                <td class="text-center">{{ $row->quantity }}</td>
                                                <td class="text-right"> {{ 'PKR ' . $row->price }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8">

                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Subtotal</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->subtotal }}</div>
                                        </div>
                                        @if ($order->discount)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Discount</div>
                                                <div class="invoice-detail-value">{{ 'PKR ' . $order->discount }}</div>
                                            </div>
                                        @endif
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->shippingcharges }}</div>
                                        </div>
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                {{ 'PKR ' . $order->total_bill }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-md-right">
                        <div class="float-lg-left mb-lg-0 mb-3">
                            @if ($order->status == '0' || $order->status == '1')
                                <button id="statusChange" data-id="{{ $order->id }}"
                                    class="btn btn-danger btn-icon icon-left"><i class="far fa-times-circle"></i>
                                    Cancel
                                </button>
                            @endif
                        </div>
                        <a href="{{ route('order.print.pdf', $order->id) }}">
                            <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
@section('script')
    <script>
        $("body").on("click", "button#statusChange", function() {
            var id = $(this).data("id");
            swal({
                    title: 'Are you sure?',
                    text: "Once Continue, you will not be able to recover",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('/order/cancel') }}' + '/' + id;
                        $.ajax({
                            url: url,
                            type: 'GET',
                            dataType: 'json',
                            // data: {
                            // "id": id,
                            // "_token": token,
                            // },
                            beforeSend: function() {
                                $(".loader").show();
                            },
                            complete: function() {
                                $(".loader").hide();
                            },
                            success: function(response) {
                                var typeOfResponse = response.type;
                                var res = response.msg;
                                if (typeOfResponse == 0) {
                                    swal('Error', res, 'error');
                                } else if (typeOfResponse == 1) {
                                    swal({
                                            title: 'Success',
                                            text: res,
                                            icon: 'success',
                                            type: 'success',
                                            showCancelButton: false, // There won't be any cancel button
                                            showConfirmButton: true // There won't be any confirm button
                                        })
                                        .then((ok) => {
                                            if (ok) {
                                                location.reload();
                                            }
                                        });
                                }
                            }
                        });
                    }
                });
        });
    </script>
@endsection

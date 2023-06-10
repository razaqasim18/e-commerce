@extends('layouts.admin')
@section('title')
    Admin || Dashboard
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
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->shippingcharges }}</div>
                                        </div>
                                        @if ($order->discount)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Discount</div>
                                                <div class="invoice-detail-value">{{ 'PKR ' . $order->discount }}</div>
                                            </div>
                                        @endif
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
                            @if ($order->status == '0')
                                <button id="statusChange" data-id="{{ $order->id }}" data-status="1"
                                    class="btn btn-info btn-icon icon-left">
                                    Processing
                                </button>
                            @elseif ($order->status == '1')
                                <button id="deliveryModal" data-id="{{ $order->id }}" data-status="2"
                                    class="btn btn-warning btn-icon icon-left">
                                    Approved
                                </button>
                                <button id="statusChange" data-id="{{ $order->id }}" data-status="-1"
                                    class="btn btn-danger btn-icon icon-left">
                                    Cancel
                                </button>
                            @elseif ($order->status == '2')
                                <button id="statusChange" data-id="{{ $order->id }}" data-status="3"
                                    class="btn btn-success btn-icon icon-left">
                                    Delivered
                                </button>
                            @endif
                            <button id="deleteButton" data-id="{{ $order->id }}"
                                class="btn btn-danger btn-icon icon-left">Delete</button>
                        </div>
                        <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div id="approvalModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Add Delivery Detail</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" id="orderid" name="orderid" required>
                        <input type="hidden" class="form-control" id="status" name="status" value="2" required>
                        <div id="customerrror"></div>
                        <div class="form-group row">
                            <label for="delivery_by" class="col-sm-3">Courier Company</label>
                            <input id="delivery_by" type="text"
                                class="col-sm-8 form-control @error('delivery_by') is-invalid @enderror"
                                name="delivery_by" value="{{ old('delivery_by') }}" required>
                        </div>
                        <div class="form-group row">
                            <label for="delivery_trackingid" class="col-sm-3">Order Tracking ID</label>
                            <input id="delivery_trackingid" type="text"
                                class="col-sm-8 form-control @error('delivery_trackingid') is-invalid @enderror"
                                name="delivery_trackingid" value="{{ old('delivery_trackingid') }}" required>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary mr-1" type="button"
                                onclick="submitApproval()">Submit</button>
                            <button class="btn btn-secondary" onclick="approvalModelClose()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("body").on("click", "button#deleteButton", function() {
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/order/delete') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
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

            $("body").on("click", "button#statusChange", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
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
                            var url = '{{ url('/admin/order/change') }}' + '/' + status + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'GET',
                                dataType: 'json',
                                // data: {
                                //     "id": id,
                                //     "_token": token,
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

            $("body").on("click", "button#deliveryModal", function() {
                $("input#orderid").val($(this).data("id"));
                $("#approvalModel").modal("show");
                // $("#approvalForm")[0].reset()
            });
        });

        function approvalModelClose() {
            $("#approvalModel").modal("hide");
            $("#approvalForm")[0].reset()
        }

        function submitApproval() {
            var myForm = $('form#approvalForm')
            if (!myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                // $myForm.find(':submit').click();
                myForm[0].reportValidity();
                return false;
            }
            let token = "{{ csrf_token() }}";
            let url = "{{ url('/admin/order/approve') }}";
            var form = $('#approvalForm')[0];
            var data = new FormData(form);
            $.ajax({
                enctype: 'multipart/form-data',
                type: "POST",
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                beforeSend: function() {
                    $(".loader").show();
                },
                complete: function() {
                    $(".loader").hide();
                },
                success: function(response) {
                    var typeOfResponse = response.type;
                    if (!typeOfResponse) {
                        if (response.validator_error) {
                            let errors = response.errors;
                            $.each(response.errors, function(key, value) {
                                $('#customerrror').append('<div class="alert alert-danger">' +
                                    value + '</div>');
                            });
                        } else {
                            let msg = response.msg;
                            swal('Error', msg, 'error');
                        }
                    } else {
                        if (typeOfResponse == 0) {
                            var res = response.msg;
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            var res = response.msg;
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
                }
            });
        }
    </script>
@endsection

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Order #{{ $order['order_no'] }} Detail</title>
    <link rel='shortcut icon' type='image/x-icon'
        href='{{ SettingHelper::getSettingValueBySLug('site_favicon') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_favicon')) : asset('img/favicon.ico') }}' />
</head>

<body>

    <div class="p-5" style="margin: 5px;">
        <div style="font-family: 'Roboto', sans-serif; width: 100%; clear:both;">
            <div style="text-align:left; width: 50%; float: left;">
                <h3>Invoice</h3>
            </div>
            <div style="text-align:right; width: 50%;float: left;">
                <h3>Order #{{ $order['order_no'] }}</h3>
            </div>
        </div>

        <div style="font-family: 'Roboto', sans-serif; width: 100%; clear:both;">
            <div style="text-align:left; width: 50%; float: left;">
                <p><b>Billed To:</b></p>
                <p>{{ $orderShippingDetail['name'] }}</p>
                <p>{{ $orderShippingDetail['email'] }}</p>
                <p>{{ $orderShippingDetail['phone'] }}</p>
            </div>
            <div style="text-align:right; width: 50%;float: left;">
                <p><b>Shipped To:</b></p>
                <p>{{ $orderShippingDetail['address'] }}</p>
                <p>{{ $orderShippingDetail['street'] }}</p>
                <p>{{ \DB::table('cities')->where('id', $orderShippingDetail['city_id'])->get()[0]->city }}</p>
            </div>
            <br />
        </div>

        <div style="font-family: 'Roboto', sans-serif; width: 100%; clear:both;">
            <div style="text-align:left; width: 50%; float: left"; <p><b>Payment Method:</b></p>
                @if ($order['payment_by'] == 1)
                    Wallet
                @elseif ($order['payment_by'] == 2)
                    Cash Back
                @else
                    Cash
                @endif
            </div>
            <div style="text-align:right; width: 50%;float: left;">
                <p><b>Order Date:</b></p>
                <p>{{ date('d M Y', strtotime($order['created_at'])) }}</p>
            </div>
        </div>

        <div>
            <h3><b>Order Summary</b></h3>
            <p>All items here cannot be deleted.</p>
            <div style="font-family: 'Roboto', sans-serif;margin-bottom: 20px;">
                <table style="width: 100%; text-align: center; border:2px solid black">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Totals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($orderDetail as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row['product'] }}</td>
                                <td class="text-center">{{ $row['points'] }}</td>
                                <td class="text-center">{{ $row['quantity'] }}</td>
                                <td class="text-right"> {{ 'PKR ' . $row['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-family: 'Roboto', sans-serif; width: 100%; clear:both;">
            <div style="text-align:right; width: 100%;float: left;">
                <p>
                    Subtotal <br />
                    <b>{{ 'PKR ' . $order['subtotal'] }}</b>
                </p>
                <p>
                    Discount <br />
                    <b>{{ 'PKR ' . $order['discount'] }}</b>
                </p>
                <p>
                    Shipping <br />
                    <b>{{ 'PKR ' . $order['shippingcharges'] }}</b>
                </p>
                <p>
                    Total <br />
                    <b>{{ 'PKR ' . $order['total_bill'] }}</b>
                </p>
            </div>
        </div>
    </div>

</body>

</html>

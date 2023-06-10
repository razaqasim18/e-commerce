<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::where('user_id', Auth::guard('web')->user()->id)->orderBy('orders.id', 'DESC')->get();
        return view('user.order.list', ['order' => $order]);
    }

    public function detail($id)
    {
        $order = Order::findOrFail($id);
        return view('user.order.detail', compact('order'));
    }

    public function printPDF($id)
    {
        $order = Order::findOrFail($id);
        $orderDetail = Order::findOrFail($id)->orderDetail;
        $orderShippingDetail = Order::findOrFail($id)->orderShippingDetail;

        $data = [
            'order' => $order->toArray(),
            'orderDetail' => $orderDetail->toArray(),
            'orderShippingDetail' => $orderShippingDetail->toArray(),
        ];
        // return view('vendor.print.pdf', compact('data'));
        $pdf = Pdf::loadView('vendor.print.pdf', $data);
        return $pdf->download($order->order_no . '_print.pdf');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if (\Order::remove($id)) {
            $json = ['type' => 1, 'msg' => 'Product is removed from cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function cancel($id)
    {
        $status = "-1";
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);
        $msg = 'Your order no ' . $order->order_no . " is cancelled";
        $orderShipping = $order->orderShippingDetail;

        $order->status = $status;
        $response = $order->save();
        $user->notify(new OrderStatus($order, $msg, $orderShipping));
        if ($response) {
            $json = ['type' => 1, 'msg' => $msg];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}

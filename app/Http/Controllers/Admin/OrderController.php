<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\assignPointsToUserAndParentsJob;
use App\Models\Commission;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Point;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::select("*", "orders.id AS id")->join('users', 'users.id', '=', 'orders.user_id')->orderBy('orders.id', 'DESC')->get();
        return view('admin.order.list', ['order' => $order]);
    }

    public function detail($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.detail', compact('order'));
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
        if (Order::destroy($id)) {
            $json = ['type' => 1, 'msg' => 'Order is deleted'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function changeStatus($status, $id)
    {
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);
        $ordershipping = $order->orderShippingDetail;
        $msg = '';
        if ($status == 1) {
            $msg = 'Your order no ' . $order->order_no . " is in process";
        } elseif ($status == 3) {
            $msg = 'Your order no ' . $order->order_no . " is delivered";
            if ($order->discount > 0) {
                $wallet = Wallet::updateOrCreate(
                    ['user_id' => $user->id],
                    ['gift' => DB::raw('gift + ' . $order->discount)],
                );
                WalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'amount' => $order->discount,
                    'is_gift' => 0,
                    'status' => 1,
                ]);
            }

            $assignPointsToUserAndParentsJob = new assignPointsToUserAndParentsJob($user->id, $user->id, $order->points);
            Queue::push($assignPointsToUserAndParentsJob);

            // its not working for two tables
            // $singleUserRankJob = new singleUserRankJob($user->id);
            // Queue::push($singleUserRankJob);

            $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $user->id)->first();
            if ($user) {
                $commission = Commission::all();
                foreach ($commission as $commissionrow) {
                    if ($user->point >= $commissionrow->points) {
                        Point::updateOrCreate(
                            ['user_id' => $user->userid],
                            ['commission_id' => $commissionrow->id]
                        );
                        if ($user->commission_id == null || $user->commission_id < $commissionrow->id) {
                            DB::transaction(function () use ($user, $commissionrow) {
                                $wallet = Wallet::updateOrCreate(
                                    ['user_id' => $user->userid],
                                    ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                                );
                                WalletTransaction::insert([
                                    'wallet_id' => $wallet->id,
                                    'amount' => $commissionrow->gift,
                                    'is_gift' => 1,
                                    'status' => 1,
                                ]);
                            });
                        }
                    }
                }
            }

            // CustomHelper::calculateUserRank($user->id);
        } else {
            $msg = 'Your order no ' . $order->order_no . " is Cancelled";
            // getting back the item from order detail
            $OrderDetail = OrderDetail::where('order_id', $id)->get();
            foreach ($OrderDetail as $item) {
                $product = Product::find($item->product_id);
                $product->stock = $product->stock + $item->quantity;
                $product->in_stock = 1;
                $product->save();
            }
        }
        $order->status = $status;
        $response = $order->save();
        $user->notify(new OrderStatus($order, $msg, $ordershipping));
        if ($response) {
            $json = ['type' => 1, 'msg' => $msg];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function orderApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_trackingid' => 'required|unique:orders',
            'delivery_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => 0,
                'validator_error' => 1,
                'errors' => $validator->errors(),
            ]);
        }
        $order = Order::findOrFail($request->orderid);
        $user = User::findOrFail($order->user_id);
        $order->delivery_by = $request->delivery_by;
        $order->delivery_trackingid = $request->delivery_trackingid;
        $order->status = '2';
        $response = $order->save();
        $ordershipping = $user->orderShippingDetail;

        $msg = 'Your order no ' . $order->order_no . " is approved";
        $user->notify(new OrderStatus($order, $msg, $ordershipping));

        if ($response) {
            $json = ['type' => 1, 'msg' => $msg];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}

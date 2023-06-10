<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $order = [
            'pending' => Order::select(DB::raw('COUNT(id) as count'))->where('status', '0')->where('user_id', Auth::guard('web')->user()->id)->first(),
            'inprocess' => Order::select(DB::raw('COUNT(id) as count'))->where('status', '1')->where('user_id', Auth::guard('web')->user()->id)->first(),
            'approved' => Order::select(DB::raw('COUNT(id) as count'))->where('status', '2')->where('user_id', Auth::guard('web')->user()->id)->first(),
            'delivered' => Order::select(DB::raw('COUNT(id) as count'))->where('status', '3')->where('user_id', Auth::guard('web')->user()->id)->first(),
            'cancelled' => Order::select(DB::raw('COUNT(id) as count'))->where('status', '-1')->where('user_id', Auth::guard('web')->user()->id)->first(),
            'total' => Order::select(DB::raw('COUNT(id) as count'))->where('user_id', Auth::guard('web')->user()->id)->first(),
        ];

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $user = [
            "point" => Point::where('user_id', Auth::guard('web')->user()->id)->first(),
            "wallet" => Wallet::where('user_id', Auth::guard('web')->user()->id)->first(),
            "personalpoint" => PointTransaction::select(DB::raw("SUM(point) as count"))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->first(),
            "personalmonthlyearning" => Wallet::select(DB::raw("SUM(wallet_transactions.amount) as count"))
                ->join("wallet_transactions", "wallet_transactions.wallet_id", "=", "wallets.id")
                ->where('wallets.user_id', Auth::guard('web')->user()->id)
                ->where('wallet_transactions.status', 1)
                ->where('wallet_transactions.is_gift', 0)
                ->whereMonth('wallet_transactions.created_at', $currentMonth)->first(),
        ];
        return view('user.home', compact('order', 'user'));
    }
}

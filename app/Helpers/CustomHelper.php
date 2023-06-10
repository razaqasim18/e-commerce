<?php // Code within app\Helpers\SettingHelper.php
namespace App\Helpers;

use App\Models\Commission;
use App\Models\EpinRequest;
use App\Models\Order;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomHelper
{
    public static function createNewEpin()
    {
        $count = 1;
        do {
            $newepin = Str::substr(Str::replace("-", "", Str::uuid()), 0, 12);
            $response = EpinRequest::where('epin', $newepin)->first();
            $count = (!$response) ? 0 : 1;
        } while ($count);
        return $newepin;
    }

    public static function getUserWalletAmountByid($id)
    {
        $wallet = Wallet::where('user_id', $id)->first();
        return ($wallet) ? $wallet->amount : "0";
    }

    public static function getUserWalletGiftByid($id)
    {
        $wallet = Wallet::where('user_id', $id)->first();
        return ($wallet) ? $wallet->gift : "0";
    }

    public static function createNewOrderno()
    {
        $count = 1;
        do {
            $newepin = Str::substr(Str::replace("-", "", Str::uuid()), 0, 12);
            $response = Order::where('order_no', $newepin)->first();
            $count = (!$response) ? 0 : 1;
        } while ($count);
        return $newepin;
    }

    public static function orderWalletTrasection($userid, $totalpay)
    {
        $wallet = Wallet::where('user_id', $userid)->first();
        $wallet->amount = $wallet->amount - $totalpay;
        $walletresponse = $wallet->save();

        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $totalpay,
            'status' => 0,
        ]);

        $walletresponse = $wallettransaction = true;
        if (!($walletresponse && $wallettransaction)) {
            return false;
        }
        return true;
    }

    public static function orderWalletGiftTrasection($userid, $totalpay)
    {
        $wallet = Wallet::where('user_id', $userid)->first();
        $wallet->gift = $wallet->gift - $totalpay;
        $walletresponse = $wallet->save();

        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $totalpay,
            'status' => 0,
            'is_gift' => '1',
        ]);

        $walletresponse = $wallettransaction = true;
        if (!($walletresponse && $wallettransaction)) {
            return false;
        }
        return true;
    }

    public static function strWordCut($string, $length, $end = '....')
    {
        $string = strip_tags($string);

        if (strlen($string) > $length) {

            // truncate string
            $stringCut = substr($string, 0, $length);

            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . $end;
        }
        return $string;
    }

    public static function assignPointsToUserAndParents($orgid, $userid, $points)
    {
        $user = User::find($userid);
        $point = Point::updateOrCreate(
            ['user_id' => $userid],
            ['point' => DB::raw('point + ' . $points)],
        );
        PointTransaction::insert([
            'user_id' => $userid,
            'point_id' => $point->id,
            'point' => $points,
            'status' => 1,
            'is_child' => ($orgid == $userid) ? 0 : 1,
        ]);
        if ($user->sponserid !== null) {
            self::assignPointsToUserAndParents($orgid, $user->sponserid, $points);
        }
        return true;
    }

    public static function productStock()
    {
        Product::where('stock', '<=', '0')->update(['in_stock' => 0]);
    }

    // all users
    public static function calculateUsersRank()
    {
        $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
        $commission = Commission::all();
        foreach ($user as $userrow) {
            foreach ($commission as $commissionrow) {
                if ($userrow->point >= $commissionrow->points) {
                    Point::updateOrCreate(
                        ['user_id' => $userrow->userid],
                        ['commission_id' => $commissionrow->id]
                    );
                    wallet::updateOrCreate(
                        ['user_id' => $userrow->userid],
                        ['gift' => $commissionrow->gift]
                    );
                }
            }
        }

    }

    public static function calculateUserRank($userid)
    {
        $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $userid)->first();
        if ($user) {
            $commission = Commission::all();
            foreach ($commission as $commissionrow) {
                if ($user->point >= $commissionrow->points) {
                    Point::updateOrCreate(
                        ['user_id' => $userid],
                        ['commission_id' => $commissionrow->id]
                    );
                    Wallet::updateOrCreate(
                        ['user_id' => $userid],
                        ['gift' => $commissionrow->gift]
                    );
                }
            }
        }
    }

    public static function calculateAllPoints($userid)
    {
        $date = date("Y-m-d");
        $usertotalpoint = DB::table('point_transactions')
            ->select(DB::raw("SUM(point) as totapoint"))
            ->where('status', '1')
            ->where('user_id', $userid)
            ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            ->first();

        $childuser = DB::table('users')
            ->where('sponserid', $userid)
            ->where('is_deleted', 0)
            ->where('is_blocked', 0)
            ->get();

        // Calculate the points for each child user recursively
        $childPoints = 0;
        foreach ($childuser as $row) {
            $childPoints = (int) $childPoints + self::calculateAllPoints($row->id);
        }

        return $usertotalpoint->totapoint + $childPoints;
    }

    public static function calculatePoint($userid)
    {
        $date = date("Y-m-d");
        $usertotalpoint = DB::table('point_transactions')
            ->select(DB::raw("SUM(point) as totalpoint"))
            ->where('status', '1')
            ->where('user_id', $userid)
            ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            ->first();
        return $usertotalpoint->totalpoint;
    }

    public static function directChildCommission()
    {
        $user = User::where('is_blocked', '0')->where('is_deleted', '0')->get();
        foreach ($user as $row) {
            $childpoints = self::calculateChildPoint($row->id);
            $childcommision = self::calculateCommission($childpoints, $row->id);
            if ($childcommision) {
                $wallet = Wallet::updateOrCreate(
                    ['user_id' => $row->id],
                    ['amount' => DB::raw('amount + ' . $childcommision)]
                );
                WalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'amount' => $childcommision,
                    'status' => 2,
                ]);
            }
        }
    }

    public static function calculateChildPoint($userid)
    {
        $childuser = DB::table('users')
            ->where('sponserid', $userid)
            ->where('is_deleted', 0)
            ->where('is_blocked', 0)
            ->get();
        $userpoint = Point::where('user_id', $userid)->first();
        $childPoints = 0;

        if ($userpoint && $userpoint->commission_id != null) {
            $usercommissionid = $userpoint->commission_id;
            // $currentDate = Carbon::now()->subMonth();
            $currentDate = Carbon::now();
            $currentMonth = $currentDate->month;
            $currentYear = $currentDate->year;
            foreach ($childuser as $row) {
                $usertotalpoint = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as totalpoint"))
                    ->where('status', '1')
                // ->where('is_child', '1')
                    ->where('user_id', $row->id)
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->first();

                $rowpoint = Point::where('user_id', $row->id)->first();
                if ($rowpoint && $usercommissionid > $rowpoint->commission_id && $rowpoint != null) {
                    $childPoints = $childPoints + $usertotalpoint->totalpoint;
                }

            }
        }
        return $childPoints;
    }

    public static function calculateCommission($points, $userid)
    {

        $point = Point::where('user_id', $userid)->first();
        $calculatedcommision = 0;
        if ($point) {
            if ($point->commission_id != null) {
                $commission = Commission::findorFail($point->commission_id);
                // $currentDate = Carbon::now()->subMonth();
                $currentDate = Carbon::now();
                $currentMonth = $currentDate->month;
                $currentYear = $currentDate->year;

                //current user points
                $usertotalpoint = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as totalpoint"))
                    ->where('status', '1')
                    ->where('user_id', $userid)
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->first();

                $childuser = DB::table('users')
                    ->where('sponserid', $userid)
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->get();

                foreach ($childuser as $row) {
                    $childpoint = Point::where('user_id', $row->id)->first();
                    if ($childpoint) {
                        if ($childpoint->commission_id != null && $childpoint->commission_id < $point->commission_id) {
                            $childcommission = Commission::findorFail($childpoint->commission_id);
                            if ($commission->ptp == null || $usertotalpoint->totalpoint >= $commission->ptp) {
                                $calculatedcommision = ($points * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                            }
                        } else {
                            if ($commission->ptp == null || $usertotalpoint->totalpoint >= $commission->ptp) {
                                $calculatedcommision = ($points * ($commission->profit / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                            }
                        }
                    }
                }

                // if ($commission->ptp == null || $usertotalpoint->totalpoint >= $commission->ptp) {
                //     $calculatedcommision = ($points * ($commission->profit / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                //     $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                // }
            }
        }
        return $calculatedcommision;
    }

}

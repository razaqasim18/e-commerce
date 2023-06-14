<?php

namespace App\Helpers;

use App\Models\OrderDiscount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CartHelper
{
    public static function cartDiscountCount($userid)
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $result = OrderDiscount::select(DB::raw("Count(id) as countitem"))->where('user_id', $userid)->whereMonth('created_at', $currentMonth)->first();
        if ($result->countitem >= 3) {
            return true;
        } else {
            return false;
        }
    }
}

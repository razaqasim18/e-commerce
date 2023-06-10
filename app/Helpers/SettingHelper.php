<?php // Code within app\Helpers\SettingHelper.php
namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }

    public static function getSettingValueBySLug($slug)
    {
        $response = Setting::where('setting_slug', $slug)->first();
        return ($response) ? $response->setting_value : "";
    }
}

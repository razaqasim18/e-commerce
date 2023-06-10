<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function loadSiteSetting()
    {
        return view('admin.setting.site');
    }

    public function saveSiteSetting(Request $request)
    {
        $sitelogo = null;
        $data = [];
        if (!empty($request->file('site_logo'))) {
            $sitelogo = time() . 'logo.' . $request->file('site_logo')->extension();
            $request
                ->file('site_logo')
                ->move(public_path('uploads/setting'), $sitelogo);
        } else {
            $sitelogo = $request->sitelogoimage;
        }
        if ($sitelogo) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_logo'],
                ['setting_value' => $sitelogo]
            );
        }

        $sitefavicon = null;
        if (!empty($request->file('site_favicon'))) {
            $sitefavicon = time() . 'icon.' . $request->file('site_favicon')->extension();
            $request
                ->file('site_favicon')
                ->move(public_path('uploads/setting'), $sitefavicon);
        } else {
            $sitefavicon = $request->sitefaviconimage;
        }

        if ($sitefavicon) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_favicon'],
                ['setting_value' => $sitefavicon]
            );
        }

        if ($request->site_name) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_name'],
                ['setting_value' => $request->site_name]
            );
        }

        if ($request->site_primary_color) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_primary_color'],
                ['setting_value' => $request->site_primary_color]
            );
        }

        if ($request->site_secondary_color) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_secondary_color'],
                ['setting_value' => $request->site_secondary_color]
            );
        }

        if ($setting) {
            return redirect()
                ->route('admin.setting.site')
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.setting.site')
                ->with('error', 'Something went wrong');
        }

    }

    public function loadChargesSetting()
    {
        return view('admin.setting.charges');
    }

    public function saveChargesSetting(Request $request)
    {

        $this->validate($request, [
            'epin_charges' => 'required|integer|min:0',
            'transection_charges' => 'required|integer|min:0',
            'gst_charges' => 'required|integer|min:0',
            'shipping_charges' => 'required|integer|min:0',
            'admin_charges' => 'required|integer|min:0',
            'money_rate' => 'required|integer|min:0',
        ]);

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'epin_charges'],
            ['setting_value' => $request->epin_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'transection_charges'],
            ['setting_value' => $request->transection_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'gst_charges'],
            ['setting_value' => $request->gst_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'shipping_charges'],
            ['setting_value' => $request->shipping_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'admin_charges'],
            ['setting_value' => $request->admin_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'money_rate'],
            ['setting_value' => $request->money_rate]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'coupon_discount'],
            ['setting_value' => $request->coupon_discount]
        );

        if ($setting) {
            return redirect()
                ->route('admin.setting.charges')
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.setting.charges')
                ->with('error', 'Something went wrong');
        }

    }

    public function bannerSetting()
    {
        $banner = Banner::all();
        return view('admin.setting.banner', ["banner" => $banner]);
    }

    public function saveBannerSetting(Request $request)
    {

        if ($request->hasFile('file')) {
            $i = 1;
            foreach ($request->file('file') as $file) {
                $banner = new Banner();
                $image = time() . $i++ . '.' . $file->extension();
                $banner->banner = $image;
                $banner->save();
                $media = $banner->addMedia($file)->toMediaCollection('images');
            }
        }
        return redirect()->route('admin.setting.banner')->with('success', 'Data is saved successfully');
    }

    public function deleteMedia($postid)
    {
        // Retrieve the model instance associated with the media file
        $banner = Banner::destroy($postid); // Replace `Post` with your actual model class and `1` with the ID of the post
        // Retrieve the media instance to be deleted by its ID
        // $mediaId = 1; // Replace `1` with the ID of the media
        // $media = $product->getMedia('images')->find($mediaId); // Replace `media_collection` with your media collection name
        if ($banner) {
            // Delete the media file, including its storage file
            // $media->delete();
            // $product->destroy();
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }
}

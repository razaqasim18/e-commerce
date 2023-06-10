<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\CheckPassword;
use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function passwordLoad()
    {
        return view('admin.profile.password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => ['required', new CheckPassword],
            'password' => ['required'],
        ]);

        $userresponse = Admin::where("id", Auth::guard('admin')->user()->id)->update(
            [
                "password" => Hash::make($request->password),
            ]);

        if ($userresponse) {
            return redirect()
                ->route('admin.password.load')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.password.load')
                ->with('error', 'Something went wrong');
        }
    }
}

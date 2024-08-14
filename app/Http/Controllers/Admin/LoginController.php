<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

use Illuminate\Http\Request;

class LoginController
{
    function login()

    {

        $sanu = Admin::all();

        return view('admin.login');
    }


    function logout()

    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }


    function doLogin()

    {
        $input = request()->only(['username', 'password']);
        $remember_me = request('remember_me');
        if (auth()->guard('admin')->attempt($input, $remember_me)) {
            return   redirect()->route('admin.csv.import');
        } else {
            return   "login Error";
        }
    }
}

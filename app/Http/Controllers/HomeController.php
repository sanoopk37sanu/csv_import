<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use Throwable;

class HomeController
{
    function home()
    {
        return view('users.home');
    }


    function Exceptions_check()
    {
        try {
        } catch (Throwable $th) {
            return response()->json(['status' => 100]);
        } catch (\Exception $e) {
            return response()->json(['status' => 200]);
        }
    }
}

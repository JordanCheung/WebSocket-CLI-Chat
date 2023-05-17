<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        if (!Auth::guard('profile')->check()) {
            return redirect('home');
        }

        return view('dashboard');
    }
}

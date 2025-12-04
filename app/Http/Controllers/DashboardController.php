<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function orders()
    {
        $orders = Auth::user()
            ->orders()
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.orders', compact('orders'));
    }


    public function addresses()
    {
        $user = Auth::user();

        return view('dashboard.addresses', compact('user'));
    }
}

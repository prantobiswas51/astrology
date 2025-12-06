<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $orders = Order::with(['orderItems.product', 'orderItems.product.files'])
                   ->where('user_id', Auth::id())
                   ->get();

        return view('dashboard', compact('orders'));
    }
}

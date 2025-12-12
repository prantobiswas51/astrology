<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }

    public function shop()
    {
        $pub_products = Product::where('status', 'published')->get();
        return view('shop', compact('pub_products'));
    }
}

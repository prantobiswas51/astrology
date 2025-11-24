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

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('layouts.single_product', compact('product'));
    }   
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product_files = $product->files;
        return view('layouts.single_product', compact('product', 'product_files'));
    }
}

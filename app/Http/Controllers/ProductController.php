<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $published = $product->status == 'published';

        if (!$published) {
            abort(404);
        }

        $product_files = $product->files;
        return view('layouts.single_product', compact('product', 'product_files'));
    }

    public function add_to_cart(Request $request)
    {
        dd($request->all());
    }
}

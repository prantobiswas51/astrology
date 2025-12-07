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


    public function downloadFile($id)
    {
        $file = \App\Models\ProductFile::findOrFail($id);

        // Get the related order item and order
        $orderItem = $file->orderItems()->first();
        $order = $orderItem->order ?? null;

        if (!$order) {
            abort(404, 'Order not found.');
        }

        // Check if the order is paid
        if ($order->status !== 'Paid') {
            abort(403, 'You need to complete the payment to download this file.');
        }

        // Return download response
        // dd(asset('storage/app/' . $file->file_path), $file->file_name);
        return response()->download(asset('storage/app/' . $file->file_path));
    }
}

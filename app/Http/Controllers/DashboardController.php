<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'orderItems.product.files'])
            ->where('user_id', Auth::id())
            ->get();

        return view('dashboard', compact('orders'));
    }


    public function downloadFile($id, $order_id)
    {

        // return $id;
        $file = ProductFile::findOrFail($id);
        $file_path = $file->file_path;

        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();


        if ($order->user_id !== $user->id) {
            abort(403, 'You do not have permission to download this file.');
        }

        // Check if the order is paid
        if ($order->status !== 'Paid') {
            abort(403, 'You need to complete the payment to download this file.');
        }

        $path = storage_path('app/' . $file_path);

        // update order status to completed if not already
        if ($order->status !== 'Completed') {
            $order->status = 'Completed';
            $order->save();
        }

        return response()->download($path);
    }
}

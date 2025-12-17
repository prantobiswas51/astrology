<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ProductFile;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    private $stripe_api_key;
    private $endpoint_secret;

    public function __construct()
    {
        $this->stripe_api_key = Setting::value('stripe_api_key');
        $this->endpoint_secret = Setting::value('endpoint_secret');
    }

    public function createCheckout(Request $request)
    {

        // Validate product ID and quantity
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::find($request->product_id);
        $selectedFiles = $request->input('files', []);

        if (!is_array($selectedFiles)) {
            $selectedFiles = [$selectedFiles];  //Array
        }

        $numberOfFiles = count($selectedFiles);

        if ($product->type != 'digital') {
            $request->validate([
                'fields' => 'required|array',
            ]);
        } else {
            $request->validate([
                'fields' => 'nullable|array',
            ]);
        }

        // Validate custom fields dynamically
        foreach ($request->fields as $key => $value) {
            if (empty($value)) {
                return response()->json([
                    'success' => false,
                    'message' => "Field {$key} is required.",
                ], 400);
            }
        }

        // dd($request->all());

        // Example: validate email field
        $email = $request->input('email');

        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email format.',
                ], 400);
            }
        }

        // dd($request->all());


        $product = \App\Models\Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        $customer_email = $email;
        $name_zodiac = $request->fields['fields[name_zodiac]'] ?? null;
        $birht_date = $request->fields['fields[dob]'] ?? null;
        $birth_time = $request->fields['fields[tob]'] ?? null;
        $birth_place = $request->fields['fields[pob]'] ?? null;
        $gender = $request->fields['fields[gender]'] ?? null;
        $detail_question = $request->fields['fields[detailed_qs]'] ?? null;
        $cell_number = $request->fields['fields[cell_number]'] ?? null;
        $insta_id = $request->fields['fields[insta_id]'] ?? null;

        $p_dob = $request->fields['fields[p_dob]'] ?? null;
        $p_tob = $request->fields['fields[p_tob]'] ?? null;
        $p_pob = $request->fields['fields[p_pob]'] ?? null;
        $p_gender = $request->fields['fields[p_gender]'] ?? null;
        $p_name_zodiac = $request->fields['fields[p_name_zodiac]'] ?? null;
        $additional_field = $request->fields['fields[additional_field]'] ?? null;
        $customer_note = $request->fields['fields[customer_note]'] ?? null;

        if ($numberOfFiles) {
            $before_price = ($product->sale_price ?? $product->price);
            $final_price = $before_price * $numberOfFiles;
        } else {
            $final_price = ($product->sale_price ?? $product->price);
        }

        Stripe::setApiKey($this->stripe_api_key);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $final_price * 100, // cents
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => url('payment/success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('payment/cancel') . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        $order = new \App\Models\Order();
        $order->email = $customer_email;
        $order->user_id = Auth::id() ?? null;
        $order->total_amount = $final_price;
        $order->stripe_session_id = $session->id;
        $order->order_status = 'Unpaid';
        $order->status = 'Pending';
        $order->save();

        // I need to save custom fields which are not null, if null ignore them
        $orderItem = new \App\Models\OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $product->id;
        $orderItem->quantity = $quantity;
        $orderItem->price = ($product->sale_price ?? $product->price);

        // allow users to save files in the dashboard and Mail them the links after purchase

        $extra = [
            'name_zodiac' => $name_zodiac,
            'birth_date' => $birht_date,
            'birth_time' => $birth_time,
            'birth_place' => $birth_place,
            'gender' => $gender,
            'detail_question' => $detail_question,
            'cell_number' => $cell_number,
            'insta_id' => $insta_id,
            'file_ids' => $selectedFiles,
            'p_dob' => $p_dob,
            'p_tob' => $p_tob,
            'p_pob' => $p_pob,
            'p_gender' => $p_gender,
            'p_name_zodiac' => $p_name_zodiac,
            'additional_field' => $additional_field,
            'customer_note' => $customer_note,
        ];

        // Remove all null or empty values
        $extra = array_filter($extra, function ($v) {
            return !is_null($v) && $v !== '';
        });

        $orderItem->extra_information = json_encode($extra);
        $orderItem->save();

        return response()->json([
            'success' => true,
            'redirect_url' => $session->url,
        ]);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $session = Session::retrieve($request->session_id);

        Log::info("Success page loaded for session: " . $session->id);

        // Fetch order (status already updated by webhook)
        $order = Order::where('stripe_session_id', $session->id)
            ->with('user', 'orderItems.product')
            ->first();

        if (!$order) {
            Log::error("Order not found on success page", [
                'session_id' => $session->id
            ]);

            return view('success', ['session' => $session]);
        }

        // Check if any order item is a digital product  
        $hasDigitalProduct = false;
        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->type == 'digital') {
                $hasDigitalProduct = true;
                break;
            }
        }

        // DIGITAL PRODUCT EMAIL HANDLING (kept same)
        if ($hasDigitalProduct) {

            // Collect file IDs
            $fileIds = [];

            foreach ($order->orderItems as $item) {
                if (empty($item->extra_information)) continue;

                $extra = json_decode($item->extra_information, true);

                if (isset($extra['file_ids']) && is_array($extra['file_ids'])) {
                    $fileIds = array_merge($fileIds, $extra['file_ids']);
                }
            }

            $files = ProductFile::whereIn('id', $fileIds)->get();

            $attachmentPaths = [];
            foreach ($files as $file) {
                $fullPath = storage_path('app/public/' . $file->file_path);

                if (file_exists($fullPath)) {
                    $attachmentPaths[] = $fullPath;
                } else {
                    Log::warning('Digital product file missing', [
                        'file_id' => $file->id,
                        'path' => $fullPath
                    ]);
                }
            }

            $html = view('emails.digital_order_files', [
                'files' => $files,
                'order' => $order
            ])->render();

            // Send email (unchanged)
            sendCustomMail(
                $order->email,
                'Your Digital Order Files - AstrologybyMari',
                $html,
                $order->user->name ?? 'Customer',
                $attachmentPaths
            );
        }

        return view('success', ['session' => $session]);
    }


    public function cancel(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $session = null;

        if ($request->session_id) {
            $session = Session::retrieve($request->session_id);
        }

        Log::info("Cancel page loaded", [
            'session_id' => $request->session_id ?? null
        ]);

        // Do NOT update order here — webhook handles failures correctly

        return view('failed', ['session' => $session]);
    }


    public function webhook(Request $request)
    {
        Log::info('Stripe Webhook invoked');
        Log::error('Stripe Webhook invoked error web.php');
        Stripe::setApiKey($this->stripe_api_key);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = $this->endpoint_secret;

        Log::info('Stripe Webhook received', ['payload' => $payload]);

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );


            Log::info($event);

            // Handle the event
            switch ($event->type) {

                case 'checkout.session.completed':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order) {
                        $order->status = 'Paid';
                        $order->order_status = 'Processing';
                        $order->save();
                    }
                    break;

                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order && $order->status !== 'Paid') {
                        $order->status = 'Paid';
                        $order->order_status = 'Processing';
                        $order->save();
                    }
                    break;

                case 'checkout.session.async_payment_failed':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order) {
                        $order->status = 'Failed';
                        $order->order_status = 'Payment Failed';
                        $order->save();
                    }
                    break;

                case 'checkout.session.expired':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order && $order->status !== 'Paid') {
                        $order->status = 'Unpaid';
                        $order->order_status = 'Expired';
                        $order->save();
                    }
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

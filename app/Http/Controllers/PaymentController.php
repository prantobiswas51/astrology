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
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        $order = new \App\Models\Order();
        $order->email = $customer_email;
        $order->user_id = Auth::id() ?? null;
        $order->total_amount = ($product->sale_price ?? $product->price) * $quantity;
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

        Log::info("Status Paid");

        $order = \App\Models\Order::where('stripe_session_id', $session->id)->with('user', 'orderItems.product')->first();
        if ($order) {
            $order->status = 'Paid';
            $order->order_status = 'Processing';
            $order->save();
        }

        // Check if any order item is a digital product
        $hasDigitalProduct = false;
        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->type == 'digital') {
                $hasDigitalProduct = true;
                break;
            }
        }

        // DIGITAL PRODUCT EMAIL HANDLING
        if ($hasDigitalProduct) {

            // Collect all file IDs from order items
            $fileIds = [];

            foreach ($order->orderItems as $item) {

                if (empty($item->extra_information)) {
                    continue;
                }

                $extra = json_decode($item->extra_information, true);

                // Multiple files (stored as file_ids)
                if (isset($extra['file_ids']) && is_array($extra['file_ids'])) {
                    $fileIds = array_merge($fileIds, $extra['file_ids']);
                }
            }

            // Fetch files from DB (adjust model name if different)
            $files = ProductFile::whereIn('id', $fileIds)->get();

            // Convert file paths to full storage paths
            $attachmentPaths = [];
            foreach ($files as $file) {
                $attachmentPaths[] = storage_path('app/' . $file->path);
            }

            $html = view('emails.digital_order_files', ['files' => $files, 'order' => $order])->render();

            // Send email with file attachments
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

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'failed') {
            Log::info("Status Paid");

            $order = \App\Models\Order::where('stripe_session_id', $session->id)->first();
            if ($order) {
                $order->status = 'Failed';
                $order->order_status = 'Failed';
                $order->save();
            }
        }

        return view('success', ['session' => $session]);
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = $this->endpoint_secret;
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );

            // Handle the event
            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    // Payment was successful
                    // You can update order in DB here
                    Log::info('Stripe Checkout Session created', ['session_id' => $session->id]);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    // Payment failed
                    Log::error('Stripe Payment Intent failed', ['payment_intent_id' => $paymentIntent->id]);
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

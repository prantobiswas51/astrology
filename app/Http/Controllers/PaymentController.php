<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;
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

        if (empty($this->stripe_api_key)) {
            Log::channel('stripe')->critical('Stripe API key is missing from settings table. All Stripe calls will fail.');
        }

        if (empty($this->endpoint_secret)) {
            Log::channel('stripe')->critical('Stripe webhook endpoint secret is missing from settings table. Webhook signature verification will fail.');
        }
    }

    public function createCheckout(Request $request)
    {
        Log::channel('stripe')->info('Stripe checkout request received', [
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'user_id' => Auth::id(),
            'url' => $request->fullUrl(),
        ]);

        // Validate product ID and quantity
        // NOTE: max:20 is a sanity cap to stop runaway/accidental quantities from
        // producing huge unintended charges (a large first-time live charge is
        // exactly the kind of transaction issuing banks auto-decline as fraud).
        // Adjust the max if your catalog legitimately needs larger bulk orders.
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:20',
        ]);

        $product = Product::findOrFail($request->product_id);

        $selectedFiles = $request->input('files', []);
        if (!is_array($selectedFiles)) {
            $selectedFiles = [$selectedFiles];
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

        // ✅ Safe check (prevents crash if fields is null)
        if (is_array($request->fields)) {
            foreach ($request->fields as $key => $value) {
                if (empty($value)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Field {$key} is required.",
                    ], 400);
                }
            }
        }

        // Email validation
        $email = $request->input('email');
        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email format.',
            ], 400);
        }

        $quantity = $request->quantity;

        // Custom fields (unchanged)
        $name_zodiac     = $request->fields['fields[name_zodiac]'] ?? null;
        $birht_date      = $request->fields['fields[dob]'] ?? null;
        $birth_time      = $request->fields['fields[tob]'] ?? null;
        $birth_place     = $request->fields['fields[pob]'] ?? null;
        $gender          = $request->fields['fields[gender]'] ?? null;
        $detail_question = $request->fields['fields[detailed_qs]'] ?? null;
        $cell_number     = $request->fields['fields[cell_number]'] ?? null;
        $insta_id        = $request->fields['fields[insta_id]'] ?? null;

        $p_dob          = $request->fields['fields[p_dob]'] ?? null;
        $p_tob          = $request->fields['fields[p_tob]'] ?? null;
        $p_pob          = $request->fields['fields[p_pob]'] ?? null;
        $p_gender       = $request->fields['fields[p_gender]'] ?? null;
        $p_name_zodiac  = $request->fields['fields[p_name_zodiac]'] ?? null;
        $additional_field = $request->fields['fields[additional_field]'] ?? null;
        $customer_note    = $request->fields['fields[customer_note]'] ?? null;

        // Price calculation (unchanged)
        if ($numberOfFiles) {
            $before_price = ($product->sale_price ?? $product->price);
            $final_price  = $before_price * $numberOfFiles;
        } else {
            $final_price = ($product->sale_price ?? $product->price);
        }

        Stripe::setApiKey($this->stripe_api_key);

        Log::channel('stripe')->info('Computed checkout total', [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'number_of_files' => $numberOfFiles,
            'unit_price' => $product->sale_price ?? $product->price,
            'final_price' => $final_price,
        ]);

        $order = new \App\Models\Order();
        $order->email        = $email;
        $order->user_id      = Auth::id();
        $order->total_amount = $final_price;
        $order->order_status = 'Unpaid';
        $order->status       = 'Pending';
        $order->save();

        try {

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',

                'metadata' => [
                    'order_number' => (string) $order->id,
                    'wp_user_id'  => (string) Auth::id() ?? $order->email,
                ],

                'payment_intent_data' => [
                    'metadata' => [
                        'order_number' => (string) $order->id,
                        'wp_user_id'   => (string) (Auth::id() ?? $order->email),
                    ],
                ],

                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => (int) ($final_price * 100),
                    ],
                    'quantity' => $quantity,
                ]],

                'success_url' => url('payment/success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => url('payment/cancel') . '?session_id={CHECKOUT_SESSION_ID}',
            ]);

            // Save session ID
            $order->stripe_session_id = $session->id;
            $order->save();
        } catch (\Throwable $e) {

            $order->status = 'CheckoutFailed';
            $order->save();

            Log::channel('stripe')->error('Stripe checkout session creation failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'order_id' => $order->id,
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create payment session: ' . $e->getMessage(),
            ], 500);
        }

        // Order Item (unchanged)
        $orderItem = new \App\Models\OrderItem();
        $orderItem->order_id   = $order->id;
        $orderItem->product_id = $product->id;
        $orderItem->quantity   = $quantity;
        $orderItem->price      = ($product->sale_price ?? $product->price);

        $extra = array_filter([
            'name_zodiac'      => $name_zodiac,
            'birth_date'       => $birht_date,
            'birth_time'       => $birth_time,
            'birth_place'      => $birth_place,
            'gender'           => $gender,
            'detail_question'  => $detail_question,
            'cell_number'      => $cell_number,
            'insta_id'         => $insta_id,
            'file_ids'         => $selectedFiles,
            'p_dob'            => $p_dob,
            'p_tob'            => $p_tob,
            'p_pob'            => $p_pob,
            'p_gender'         => $p_gender,
            'p_name_zodiac'    => $p_name_zodiac,
            'additional_field' => $additional_field,
            'customer_note'    => $customer_note,
        ], fn($v) => !is_null($v) && $v !== '');

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

        try {
            $session = Session::retrieve($request->session_id);
        } catch (\Throwable $e) {
            Log::channel('stripe')->error('Failed to retrieve Stripe session on success page', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'session_id' => $request->session_id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('success', ['session' => null]);
        }

        Log::channel('stripe')->info('Success page loaded', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status ?? null,
        ]);

        // Fetch order (status already updated by webhook)
        $order = Order::where('stripe_session_id', $session->id)
            ->with('user', 'orderItems.product')
            ->first();

        if (!$order) {
            Log::channel('stripe')->error("Order not found on success page", [
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
                    Log::channel('stripe')->warning('Digital product file missing', [
                        'order_id' => $order->id,
                        'file_id' => $file->id,
                        'path' => $fullPath
                    ]);
                }
            }

            try {
                $html = view('emails.digital_order_files', [
                    'files' => $files,
                    'order' => $order
                ])->render();

                // Send email (unchanged)
                sendCustomMail(
                    $order->email,
                    'Your Digital Order Files - AstrologybyMari',
                    $html,
                    $order->user->name ?? 'Guest',
                    $attachmentPaths
                );

                $order->order_status = "Completed";
                $order->save();
            } catch (\Throwable $e) {
                Log::channel('stripe')->error('Failed to send digital order files email', [
                    'order_id' => $order->id,
                    'email' => $order->email,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);

                report($e);
            }
        }

        return view('success', ['session' => $session]);
    }


    public function cancel(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $session = null;

        if ($request->session_id) {
            try {
                $session = Session::retrieve($request->session_id);
            } catch (\Throwable $e) {
                Log::channel('stripe')->error('Failed to retrieve Stripe session on cancel page', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'session_id' => $request->session_id,
                ]);
            }
        }

        Log::channel('stripe')->warning("Cancel page loaded", [
            'session_id' => $request->session_id ?? null,
            'order_id' => $session
                ? optional(Order::where('stripe_session_id', $session->id)->first())->id
                : null,
        ]);

        // Do NOT update order here — webhook handles failures correctly

        return view('failed', ['session' => $session]);
    }


    public function webhook(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = $this->endpoint_secret;

        if (empty($sig_header)) {
            Log::channel('stripe')->error('Stripe webhook received without a Stripe-Signature header', [
                'ip' => $request->ip(),
            ]);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            Log::channel('stripe')->error('Stripe webhook received invalid payload', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature — usually a wrong/stale endpoint_secret
            Log::channel('stripe')->error('Stripe webhook signature verification failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'endpoint_secret_set' => !empty($endpoint_secret),
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            Log::channel('stripe')->error('Unexpected error while parsing Stripe webhook', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json(['error' => $e->getMessage()], 400);
        }

        Log::channel('stripe')->info('Stripe webhook event received', [
            'event_id' => $event->id,
            'event_type' => $event->type,
        ]);

        try {
            // Handle the event
            switch ($event->type) {

                case 'checkout.session.completed':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order) {
                        $order->status = 'Paid';
                        $order->order_status = 'Processing';
                        $order->save();

                        Log::channel('stripe')->info('Order marked Paid via checkout.session.completed', [
                            'order_id' => $order->id,
                            'session_id' => $session->id,
                        ]);
                    } else {
                        Log::channel('stripe')->error('checkout.session.completed received but no matching order found', [
                            'session_id' => $session->id,
                        ]);
                    }

                    break;

                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order && $order->status !== 'Paid') {
                        $order->status = 'Paid';
                        $order->order_status = 'Processing';
                        $order->save();

                        Log::channel('stripe')->info('Order marked Paid via checkout.session.async_payment_succeeded', [
                            'order_id' => $order->id,
                            'session_id' => $session->id,
                        ]);
                    } elseif (!$order) {
                        Log::channel('stripe')->error('checkout.session.async_payment_succeeded received but no matching order found', [
                            'session_id' => $session->id,
                        ]);
                    }
                    break;

                case 'checkout.session.async_payment_failed':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order) {
                        $order->status = 'Failed';
                        $order->order_status = 'Payment Failed';
                        $order->save();

                        Log::channel('stripe')->warning('Order marked Failed via checkout.session.async_payment_failed', [
                            'order_id' => $order->id,
                            'session_id' => $session->id,
                            'reason' => $session->payment_intent ?? null,
                        ]);
                    } else {
                        Log::channel('stripe')->error('checkout.session.async_payment_failed received but no matching order found', [
                            'session_id' => $session->id,
                        ]);
                    }
                    break;

                case 'checkout.session.expired':
                    $session = $event->data->object;

                    $order = Order::where('stripe_session_id', $session->id)->first();

                    if ($order && $order->status !== 'Paid') {
                        $order->status = 'Expired';
                        $order->order_status = 'Cancelled';
                        $order->save();

                        Log::channel('stripe')->warning('Order marked Expired via checkout.session.expired', [
                            'order_id' => $order->id,
                            'session_id' => $session->id,
                        ]);
                    } elseif (!$order) {
                        Log::channel('stripe')->error('checkout.session.expired received but no matching order found', [
                            'session_id' => $session->id,
                        ]);
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $intent = $event->data->object;

                    Log::channel('stripe')->warning('payment_intent.payment_failed received', [
                        'payment_intent_id' => $intent->id,
                        'last_payment_error' => $intent->last_payment_error->message ?? null,
                        'metadata' => $intent->metadata ?? null,
                    ]);
                    break;

                default:
                    Log::channel('stripe')->info('Unhandled Stripe webhook event type', [
                        'event_type' => $event->type,
                        'event_id' => $event->id,
                    ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            Log::channel('stripe')->error('Error while processing Stripe webhook event', [
                'event_type' => $event->type ?? null,
                'event_id' => $event->id ?? null,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

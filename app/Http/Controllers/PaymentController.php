<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
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

        // dd($this->stripe_api_key, $this->endpoint_secret);

        // Validate product ID and quantity
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'fields' => 'required|array',
        ]);

        // Validate custom fields dynamically
        foreach ($request->fields as $key => $value) {
            if (empty($value)) {
                dd("Field {$key} is required.");
            }
        }

        // Example: validate email field
        if (isset($request->fields['fields[email]'])) {
            if (!filter_var($request->fields['fields[email]'], FILTER_VALIDATE_EMAIL)) {
                dd('Invalid email format.');
            }
        }

        $product = \App\Models\Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $customer_email = $request->fields['fields[email]'] ?? null;

        Stripe::setApiKey($this->stripe_api_key);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => ($product->sale_price ?? $product->price) * 100, // cents
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        $order = new \App\Models\Order();
        $order->email = $customer_email;
        $order->total_amount = ($product->sale_price ?? $product->price) * $quantity;
        $order->stripe_session_id = $session->id;
        $order->status = 'Pending';
        $order->save();

        return response()->json([
            'success' => true,
            'redirect_url' => $session->url,
        ]);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey($this->stripe_api_key);

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            Log::info("Status Paid");

            $order = \App\Models\Order::where('stripe_session_id', $session->id)->first();
            if ($order) {
                $order->status = 'Completed';
                $order->save();
            }
        }

        return view('payment.success', ['session' => $session]);
    }

    public function cancel()
    {
        return view('payment.cancel');
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

<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Product;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function createCheckout(Request $request)
    {
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

        Stripe::setApiKey(env('STRIPE_SECRET'));

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
        $order->product_id = $product->id;
        $order->quantity = $quantity;
        $order->amount = ($product->sale_price ?? $product->price) * $quantity;
        $order->stripe_session_id = $session->id;
        $order->custom_fields = json_encode($request->fields);
        $order->status = 'Pending';
        $order->save();

        return response()->json([
            'success' => true,
            'redirect_url' => $session->url,
        ]);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            // update order in database, etc.
        }

        return view('payment.success', ['session' => $session]);
    }

    public function cancel()
    {
        return "Payment cancelled!";
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
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

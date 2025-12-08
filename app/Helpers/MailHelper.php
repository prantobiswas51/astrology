<?php

use App\Models\Setting;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

if (!function_exists('sendCustomMail')) {

    function sendCustomMail(string $to, string $subject, string $htmlContent): void
    {
        // Prepare body
        $payload = [
            "from" => [
                "address" => "no-reply@astrologybymari.com",
                "display_name"  => "Astrology by Mari",
            ],
            "to" => [
                [
                    "address" => $to,
                    "display_name"  => Auth::user() ? Auth::user()->name : "Guest",
                ]
            ],
            "subject" => $subject,
            "html"    => $htmlContent,
        ];

        // Send email using Maileroo API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key'    => Setting::first()->maileroo_api_key ?? '',
        ])->post('https://smtp.maileroo.com/api/v2/emails', $payload);

        // Optional: log if failed
        if (!$response->successful()) {
            Log::error('Maileroo Send Failed', [
                'to'       => $to,
                'status'   => $response->status(),
                'response' => $response->body()
            ]);
        }
    }
    
}

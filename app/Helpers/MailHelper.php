<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

if (!function_exists('sendCustomMail')) {

    function sendCustomMail(string $to, string $subject, string $htmlContent, string $username = "User"): void
    {
        $apiKey = optional(Setting::first())->maileroo_api_key;

        if (empty($apiKey)) {
            Log::error("Maileroo API key missing.");
            return;
        }

        $payload = [
            "from" => [
                "address"      => "no-reply@astrologybymari.com",
                "display_name" => "Astrology by Mari",
            ],
            "to" => [
                [
                    "address"      => $to,
                    "display_name" => $username,
                ]
            ],
            "subject" => $subject,
            "html"    => $htmlContent,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key'    => $apiKey,
        ])->post('https://smtp.maileroo.com/api/v2/emails', $payload);

        if (!$response->successful()) {
            Log::error('Maileroo Send Failed', [
                'to'       => $to,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }
}

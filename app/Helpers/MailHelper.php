<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

if (!function_exists('sendCustomMail')) {

    function sendCustomMail(string $to, string $subject, string $htmlContent, string $username = "User", array $attachmentPaths = []): void
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

        // Add attachments if provided
        if (!empty($attachmentPaths)) {
            $attachments = [];
            
            foreach ($attachmentPaths as $filePath) {
                if (!file_exists($filePath)) {
                    Log::warning("Attachment file not found: {$filePath}");
                    continue;
                }

                if (!is_file($filePath)) {
                    Log::warning("Attachment path is not a file: {$filePath}");
                    continue;
                }

                $fileName = basename($filePath);
                $fileContent = file_get_contents($filePath);
                $base64Content = base64_encode($fileContent);
                
                // Determine MIME type
                $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
                
                $attachments[] = [
                    'file_name' => $fileName,
                    'content_type' => $mimeType,
                    'content' => $base64Content,
                    'inline' => false,
                ];
            }
            
            if (!empty($attachments)) {
                $payload['attachments'] = $attachments;
            }
        }

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
        } else {
            Log::info('Email sent successfully', [
                'to' => $to,
                'attachments_count' => count($attachmentPaths),
            ]);
        }
    }
}

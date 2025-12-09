<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Digital Order - Astrology by Mari</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #6366f1;
        }
        .header h1 {
            color: #6366f1;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .order-details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .order-details h2 {
            color: #6366f1;
            font-size: 18px;
            margin-top: 0;
        }
        .order-info {
            margin: 10px 0;
        }
        .order-info strong {
            color: #4b5563;
        }
        .files-list {
            margin: 20px 0;
        }
        .file-item {
            background-color: #f9fafb;
            padding: 12px 15px;
            margin: 8px 0;
            border-radius: 6px;
            border-left: 4px solid #6366f1;
        }
        .file-item strong {
            color: #6366f1;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .note strong {
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Thank You for Your Order! ✨</h1>
            <p style="color: #6b7280; margin: 10px 0 0 0;">Your digital products are ready</p>
        </div>

        <div class="content">
            <p>Hello {{ $order->user->name ?? 'Valued Customer' }},</p>
            
            <p>Thank you for your purchase! Your payment has been successfully processed, and your digital files are attached to this email.</p>

            <div class="order-details">
                <h2>📋 Order Details</h2>
                <div class="order-info">
                    <strong>Order ID:</strong> #{{ $order->id }}<br>
                    <strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}<br>
                    <strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}<br>
                    <strong>Status:</strong> <span style="color: #10b981;">{{ $order->order_status }}</span>
                </div>
            </div>

            @if($files->isNotEmpty())
            <div class="files-list">
                <h3 style="color: #6366f1;">📎 Attached Files ({{ $files->count() }}):</h3>
                @foreach($files as $file)
                <div class="file-item">
                    <strong>{{ $file->name ?? basename($file->path) }}</strong><br>
                    <small style="color: #6b7280;">{{ basename($file->path) }}</small>
                </div>
                @endforeach
            </div>
            @endif

            <div class="note">
                <strong>📥 Download Instructions:</strong><br>
                All your purchased files are attached to this email. Please download and save them to your device. If you have any trouble accessing your files, please contact us.
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to reach out to our support team.</p>

            <p style="margin-top: 30px;">
                Warm regards,<br>
                <strong style="color: #6366f1;">The Astrology by Mari Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Astrology by Mari. All rights reserved.</p>
            <p style="margin: 5px 0;">
                <a href="{{ config('app.url') }}" style="color: #6366f1; text-decoration: none;">Visit Our Website</a>
            </p>
        </div>
    </div>
</body>
</html>

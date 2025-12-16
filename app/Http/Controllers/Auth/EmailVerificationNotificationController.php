<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $user = $request->user();
        
        // Update verification token if needed
        if (empty($user->remember_token)) {
            $user->remember_token = Str::random(40);
            $user->save();
        }

        // Verification URL
        $verifyUrl = URL::to('/email-check?token=' . $user->remember_token . '&email=' . urlencode($user->email));

        // Build email HTML
        $html = '
        <div style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
                <div style="background-color: #4a90e2; color: #ffffff; padding: 20px; text-align: center;">
                    <h1 style="margin: 0; font-size: 24px;">Verify Your Email</h1>
                </div>
                <div style="padding: 30px; text-align: center;">
                    <h2 style="color: #333333;">Welcome, ' . e($user->name) . '!</h2>
                    <p style="color: #555555; font-size: 16px;">Please verify your email to activate your account.</p>
                        <a href="' . e($verifyUrl) . '"
                            style="display: inline-block; background-color: #4a90e2; color: #ffffff;
                                padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                            Verify Email
                        </a>
                </div>
                <div style="background-color: #f1f3f5; padding: 15px; text-align: center; font-size: 13px; color: #777;">
                    <p>© ' . date("Y") . ' Astrology by Mari. All rights reserved.</p>
                </div>
            </div>
        </div>
        ';

        // Send email
        sendCustomMail($user->email, 'Verify Your Email - Astrology by Mari', $html, $user->name);

        return back()->with('status', 'verification-link-sent');
    }
}

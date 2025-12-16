<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate password reset token
        $token = Password::createToken($user);
        
        // Create reset URL
        $resetUrl = URL::to('/reset-password/' . $token . '?email=' . urlencode($user->email));

        // Build email HTML
        $html = '
        <div style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
                <div style="background-color: #4a90e2; color: #ffffff; padding: 20px; text-align: center;">
                    <h1 style="margin: 0; font-size: 24px;">Reset Your Password</h1>
                </div>
                <div style="padding: 30px; text-align: center;">
                    <h2 style="color: #333333;">Hello, ' . e($user->name) . '!</h2>
                    <p style="color: #555555; font-size: 16px;">We received a request to reset your password. Click the button below to create a new password.</p>
                    <a href="' . e($resetUrl) . '"
                        style="display: inline-block; background-color: #4a90e2; color: #ffffff;
                            padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; margin: 20px 0;">
                        Reset Password
                    </a>
                    <p style="color: #777777; font-size: 14px; margin-top: 20px;">This link will expire in 60 minutes.</p>
                    <p style="color: #777777; font-size: 14px;">If you did not request a password reset, please ignore this email.</p>
                </div>
                <div style="background-color: #f1f3f5; padding: 15px; text-align: center; font-size: 13px; color: #777;">
                    <p>© ' . date("Y") . ' Astrology by Mari. All rights reserved.</p>
                </div>
            </div>
        </div>
        ';

        // Send email using custom mail function
        sendCustomMail($user->email, 'Reset Your Password - Astrology by Mari', $html, $user->name);

        return back()->with('status', 'We have emailed your password reset link!');
    }
}

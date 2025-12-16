<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add reCAPTCHA validation only in production
        if (app()->environment('production')) {
            $rules['g-recaptcha-response'] = ['required', function ($attribute, $value, $fail) {
                $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
                $response = $recaptcha->setExpectedAction('register')
                                      ->setScoreThreshold(0.5)
                                      ->verify($value, request()->ip());
                
                if (!$response->isSuccess()) {
                    $fail('reCAPTCHA verification failed. Please try again.');
                }
            }];
        }

        $request->validate($rules);

        // Create user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(40); // Verification token
        $user->save();

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
                    <p>Need help? Contact <a href="mailto:support@tappayz.com" style="color: #4a90e2;">support@tappayz.com</a></p>
                    <p>© ' . date("Y") . ' Tappayz. All rights reserved.</p>
                </div>
            </div>
        </div>
        ';

        // Send email
        sendCustomMail($user->email, 'Verify Your Email - Astrology by Mari', $html, $user->name);

        return redirect()->route('login')->with('success', 'Please check your email to verify your account.');
    }
}

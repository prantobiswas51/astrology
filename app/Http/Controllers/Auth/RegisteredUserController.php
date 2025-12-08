<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
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

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // $verifyUrl = URL::to('/email-check?token=' . $user->remember_token . '&email=' . urlencode($user->email));

        // $html = '
        // <div style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
        //     <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
        //         <div style="background-color: #4a90e2; color: #ffffff; padding: 20px; text-align: center;">
        //             <h1 style="margin: 0; font-size: 24px;">Verify Your Email</h1>
        //         </div>
        //         <div style="padding: 30px; text-align: center;">
        //             <h2 style="color: #333333;">Welcome, ' . e($user->name) . '!</h2>
        //             <p style="color: #555555; font-size: 16px;">Please verify your email to activate your account.</p>
        //                 <a href="' . e($verifyUrl) . '"
        //                     style="display: inline-block; background-color: #4a90e2; color: #ffffff;
        //                         padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold;">
        //                     Verify Email
        //                 </a>
        //         </div>
        //         <div style="background-color: #f1f3f5; padding: 15px; text-align: center; font-size: 13px; color: #777;">
        //             <p>Need help? Contact <a href="mailto:support@tappayz.com" style="color: #4a90e2;">support@tappayz.com</a></p>
        //             <p>© ' . date("Y") . ' Tappayz. All rights reserved.</p>
        //         </div>
        //     </div>
        // </div>
        // ';

        // sendCustomMail($request->email, 'Verify Your Email - Astrology by Mari', $html);

        Mail::raw("Welcome! Your account has been created.", function ($message) use ($user) {
            $message->from('no-reply@yourdomain.com', 'Astrology by Mari');
            $message->to($user->email);
            $message->subject('Welcome to Astrology by Mari');
        });


        return redirect(route('dashboard', absolute: false));
    }
}

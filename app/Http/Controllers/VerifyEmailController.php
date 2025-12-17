<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        // Find the user
        $user = User::where('email', $email)
            ->where('email_verification_token', $token)
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid verification link.',
            ]);
        }

        // If already verified
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Your email is already verified.');
        }

        // Mark verified
        $user->email_verified_at = now();
        $user->remember_token = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Email verified successfully! You can now login.');
    }
}

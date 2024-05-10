<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
      
    public function index()
    {
        return view('emails.verified-account');
    }

    public function verify($userId, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return view('emails.forbidden-error');
        }

        $user = User::findOrFail($userId);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect('/email/verified');
    }

    public function resendEmailVerification(Request $request)
    {
        $user = auth()->user();

        Mail::to($user)->send(new VerificationMail(['user' => $user]));

        return $this->formatApiResponse(200, 'Email verification link has been sent');
    }
    
}

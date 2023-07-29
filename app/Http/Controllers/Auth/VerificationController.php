<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
      
    public function verify($userId, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->formatApiResponse('403', 'Invalid or Expired url provided.');
        }

        $user = User::findOrFail($userId);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect('/verified');
    }
    
}

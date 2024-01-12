<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return $this->formatApiResponse(400, 'User does not exist');
        }

        if (!Auth::attempt($request->validated())) {
            return $this->formatApiResponse(403, 'Invalid login credentials');
        }

        $user->token = $user->createToken(User::TOKEN_NAME)->plainTextToken;
        $user->token_expiry = Carbon::now()->addMinutes(config('sanctum.expiration'));

        return $this->formatApiResponse(200, 'Login successful', ['user' => $user]);
    }

    public function logout(Request $request)
    {
        if(!auth()->user()){
            return $this->formatApiResponse(400, 'Bad request');
        }

        auth()->user()->tokens()->delete();
        return $this->formatApiResponse(200, 'Logout sucessful');
    }
}

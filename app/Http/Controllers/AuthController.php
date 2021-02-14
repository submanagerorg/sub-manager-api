<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function login(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validator->fails()) {
                return $this->formatApiResponse(422, 'Validation failed', [], $validator->errors());
            }

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return $this->formatApiResponse(403, 'Access denied');
            }
            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password) || !$user) {
                return $this->formatApiResponse(500, 'Login check failed');
            }
            $token = $user->createToken(User::TOKEN_NAME)->plainTextToken;
            $user->token = $token;
            return $this->formatApiResponse(200, 'Login successful', $user);
        } catch (Exception $e) {
            return $this->formatApiResponse(500, 'Error Occured', [], $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);

            if($validator->fails()){
                return $this->formatApiResponse(422, 'Validation failed', [], $validator->errors());
            }
            if(User::exists($request->email)){
                return $this->formatApiResponse(400, 'User already exists');
            }

            $user = User::createNew();
            if(!$user){
                return $this->formatApiResponse(500, 'Error occured');
            }
            $user->token = $user->createToken(User::TOKEN_NAME)->plainTextToken;
            return $this->formatApiResponse(201, 'Registration Successful', $user);

        }catch(Exception $e) {
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
    }
}

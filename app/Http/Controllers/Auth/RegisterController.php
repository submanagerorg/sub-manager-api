<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {   
        try{
            if(User::exists($request->email)){
                return $this->formatApiResponse(400, 'User already exists');
            }
    
            $user = User::createNew($request->validated());
    
            if(!$user){
                return $this->formatApiResponse(500, 'Error occured');
            }
    
            $user->sendEmailVerificationNotification();
    
            return $this->formatApiResponse(201, 'Registration Successful. Proceed to verify your email', $user);

        }catch(Exception $e) {
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
        
    }
}

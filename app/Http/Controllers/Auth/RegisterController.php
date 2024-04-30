<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try{
            if(User::exists($request->email)){
                return $this->formatApiResponse(400, 'User already exists');
            }

            $user = User::createNew($request->validated());

            $user->addUserPricingPlan(PricingPlan::PLANS['BASIC'], PricingPlan::PERIOD['LIFETIME']);
           
            $user->refresh();

            $user->token = $user->createToken(User::TOKEN_NAME)->plainTextToken;
            $user->token_expiry = Carbon::now()->addMinutes(config('sanctum.expiration'));

            $user->sendEmailVerificationNotification();

            DB::commit();

            return $this->formatApiResponse(201, 'Registration Successful. Proceed to verify your email',['user' => $user]);

        }catch(Throwable $e) {
            DB::rollback();

            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }

    }
}

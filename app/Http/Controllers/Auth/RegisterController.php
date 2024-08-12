<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Mail\WelcomeMail;
use App\Models\PricingPlan;
use App\Notifications\NewSignUpNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

            $pricingPlan = PricingPlan::where('name', PricingPlan::DEFAULT_PLAN)->first();

            $user->addUserPricingPlan($pricingPlan);
           
            $user->refresh();

            $user->token = $user->createToken(User::TOKEN_NAME)->plainTextToken;
            $user->token_expiry = Carbon::now()->addMinutes(config('sanctum.expiration'));

            $mail_data = [
                'user' =>  $user,
            ];

            Mail::to($user)->send(new WelcomeMail());
            Mail::to($user)->send(new VerificationMail($mail_data));

            DB::commit();

            try {
                Notification::route('slack', config('services.slack.webhook_url.info'))
                    ->notify(new NewSignUpNotification(['email' => $user->email]));

            } catch (\Throwable $e) {
                Log::error('Failed to send notification to slack: ' . $e->getMessage());
            }

            return $this->formatApiResponse(201, 'Registration Successful. Proceed to verify your email',['user' => $user]);

        }catch(Throwable $e) {
            DB::rollback();

            report($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }

    }
}

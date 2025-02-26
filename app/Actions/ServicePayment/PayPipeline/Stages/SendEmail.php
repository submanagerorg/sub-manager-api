<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Jobs\HandleServicePaymentEmail;
use App\Models\User;
use App\Notifications\FailedServicePayment;
use App\Notifications\SuccessfulServicePaymentEmail;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmail
{
    public function handle(HandlePaymentState $state, Closure $next)
    {
        try {
            $requestData = $state->getRequestData();

            

            $user = User::find($requestData['user_id']);

            if (!$user) {
                return $next($state);
            }

            Log::info("Sending email for service payment");

            $this->sendMail($state, $user, $requestData['service_name']);

            return $next($state);
        } catch (Throwable $e) {
            Log::error("Failure while trying to send email for vtpass webhook", [$e->getMessage(), $e->getTrace()]);
        }
    }

    private function sendMail(HandlePaymentState $state, User $user, string $serviceName) {
        HandleServicePaymentEmail::dispatch($state->transactionSuccessful(), $user, $serviceName);
    }
}
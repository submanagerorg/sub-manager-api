<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
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

            if ($state->transactionSuccessful()) {
                $this->sendSuccessEmail($user, $requestData['service_name']);
            } else {
                $this->sendFailueEmail($user, $requestData['service_name']);
            }

            return $next($state);
        } catch (Throwable $e) {
            Log::error("Failure while trying to send email for vtpass webhook", [$e->getMessage(), $e->getTrace()]);
        }
    }

    private function sendSuccessEmail(User $user, string $serviceName) {
        $user->notify(new SuccessfulServicePaymentEmail($serviceName));
    }

    private function sendFailueEmail(User $user, string $serviceName) {
        $user->notify(new FailedServicePayment($serviceName));
    }
}
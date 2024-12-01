<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\User;
use App\Notifications\FailedServicePayment;
use App\Notifications\SuccessfulServicePaymentEmail;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmail 
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        try {
            $metaData = $state->getMetaData();

            $user = User::find($metaData['user_id']);

            if (!$user) return;

            if ($state->transactionSuccessful()) {
                $this->sendSuccessEmail($user, $metaData['service_name']);
            } else {
                $this->sendFailueEmail($user, $metaData['service_name']);
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
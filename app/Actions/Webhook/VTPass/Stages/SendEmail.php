<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\User;
use App\Notifications\FailedServicePayment;
use App\Notifications\SuccessfulServicePaymentEmail;

class SendEmail 
{
    public function handle(HandleVTPassWebhookState $state)
    {
        $metaData = $state->getMetaData();

        $user = User::find($metaData['user_id']);

        if (!$user) return;

        if ($state->transactionSuccessful()) {
            $this->sendSuccessEmail($user, $metaData['service_name']);
        } else {
            $this->sendFailueEmail($user, $metaData['service_name']);
        }
    }

    private function sendSuccessEmail(User $user, string $serviceName) {
        $user->notify(new SuccessfulServicePaymentEmail($serviceName));
    }

    private function sendFailueEmail(User $user, string $serviceName) {
        $user->notify(new FailedServicePayment($serviceName));
    }
}
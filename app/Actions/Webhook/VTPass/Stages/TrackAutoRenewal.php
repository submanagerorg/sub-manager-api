<?php

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\AutoRenewal;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackAutoRenewal 
{
    public function handle(HandleVTPassWebhookState $state)
    {
        try {
            $metaData = $state->getMetaData();

            if ($metaData['is_tracking_disabled'] || !$metaData['auto_renew'] || $state->transactionFailed()) {
                return null;
            }

            AutoRenewal::create([
                'service_id' => $metaData['service_id'],
                'user_id' => $metaData['user_id'],
                'last_subscription_id' => $state->getSubcription()?->id
            ]);
        } catch (Throwable $e) {
            Log::error("Failure while trying to track auto renewal. ". $e->getMessage(), $e->getTraceAsString());
        }
    }
}
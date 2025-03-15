<?php

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\AutoRenewal;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackAutoRenewal 
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        try {
            $metaData = $state->getMetaData();

            if (!isset($metaData['auto_renew'])) {
                return $next($state);
            }

            if ($metaData['is_tracking_disabled'] || !$metaData['auto_renew'] || $state->transactionFailed()) {
                Log::info("Auto renew is not happening");

                return $next($state);
            }

            AutoRenewal::create([
                'service_id' => $metaData['service_id'],
                'user_id' => $metaData['user_id'],
                'last_subscription_id' => $state->getSubcription()?->id
            ]);

            Log::info("Auto renewal created");

            return $next($state);
        } catch (Throwable $e) {
            Log::error("Failure while trying to track auto renewal.", [$e->getTraceAsString()]);
        }
    }
}
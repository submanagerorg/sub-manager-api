<?php

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\Subscription;
use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackSubscription 
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        try {
            $metaData = $state->getMetaData();

            if ($metaData['is_tracking_disabled'] || $state->transactionFailed()) {
                Log::info("Subscription is not tracked");
                return;
            }

            $data = [
                'user_id' => $metaData['user_id'],
                'name' => $metaData['service_name'] . ' Subscription',
                'service_id' => $metaData['service_id'],
                'currency_id' => $metaData['currency_id'],
                'amount' => $metaData['variation_amount'],
                'start_date' => now()->toDateTimeString(),
                'end_date' => today()->addMonth(), // Todo: Find a better way to determining the end date
                'description' => $metaData['service_name'] . ' Subscription via Subsync'
            ];

            // Check if user wants to track the subscription 

            $sub = Subscription::createNew($data);

            $existingSub = Subscription::where('service_id', $metaData['service_id'])->where('user_id', $metaData['user_id'])->where('end_date', '<=', today()->addDays(3))->first();

            if ($existingSub) {
                $existingSub->status = Subscription::STATUS['EXPIRED'];
                $existingSub->save();
            }

            $state->setSubscription($sub);

            Log::info("Subscription tracked successfully");

            return $next($state);

        } catch (Throwable $err) {
            Log::error("Failure while trying to track subscription", [$err->getTraceAsString()]);
        }
    }
}
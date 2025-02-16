<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Models\Subscription;
use Closure;
use Illuminate\Support\Facades\Log;

class TrackSubscription
{
    public function handle(HandlePaymentState $state, Closure $next)
    {
        $requestData = $state->getRequestData();

        Log::info("Tracking subscription", ['requestData' => $requestData, 'tr' => $state->transactionFailed()]);

        if ($requestData['is_tracking_disabled'] || $state->transactionFailed()) {
            
            return $next($state);
        }

        $subscription = Subscription::createNew([
            'user_id' => $requestData['user_id'],
            'name' => $requestData['service_name'] . ' Subscription',
            'service_id' => $requestData['service_id'],
            'currency_id' => $requestData['currency_id'],
            'amount' => $requestData['variation_amount'],
            'start_date' => now()->toDateTimeString(),
            'end_date' => today()->addMonth(), // Todo: Find a better way of determining the end date
            'description' => $requestData['service_name'] .' '. $requestData['variation_code'] ?? null . ' Subscription via Subsync'
        ]);

        $state->setSubscription($subscription);

        Log::info("Subscription tracked successfully");

        return $next($state);
    }
}
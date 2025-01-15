<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Models\AutoRenewal;
use Closure;
use Illuminate\Support\Facades\Log;

class TrackAutoRenewal
{
    public function handle(HandlePaymentState $state, Closure $next)
    {
        $requestData = $state->getRequestData();

        if (
            ($requestData['is_auto_renewal_disabled'] ?? false) || 
            $state->transactionFailed()
            || !($requestData['auto_renew'] ?? false)
            || !($requestData['is_tracking_disabled'] ?? false)) {
            
            return $next($state);
        }

        AutoRenewal::create([
            'service_id' => $requestData['service_id'],
            'user_id' => $requestData['user_id'],
            'last_subscription_id' => $state->getSubcription()?->id
        ]);

        Log::info("Auto renewal tracked successfully");

        return $next($state);
    }
}
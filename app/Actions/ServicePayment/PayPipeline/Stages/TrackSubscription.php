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

        $startDate =  now();
        $endDate = $this->getEndDate($startDate, $requestData['period'] ?? null);

        $subscription = Subscription::createNew([
            'user_id' => $requestData['user_id'],
            'name' => ucwords($requestData['service_name']) . ' Subscription',
            'service_id' => $requestData['service_id'],
            'currency_id' => $requestData['currency_id'],
            'amount' => $requestData['variation_amount'],
            'start_date' => $startDate,
            'end_date' => $endDate, // Todo: Find a better way of determining the end date
            'description' => $requestData['variation_code'] . ' Subscription via Subsync'
        ]);

        $state->setSubscription($subscription);

        Log::info("Subscription tracked successfully");

        return $next($state);
    }

    public function getEndDate($startDate, $period) 
    {
        $startDate = clone $startDate;
        
        if($period == 'yearly'){
            return $startDate->addYear();
        }

        return $startDate->addMonth();
    }
}
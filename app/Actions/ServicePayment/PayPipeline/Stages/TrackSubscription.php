<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\Category\GetCategoriesAction;
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

        $category_id = (new GetCategoriesAction)->autoCategorize(strtolower($requestData['service_name']));

        $subscription = Subscription::createNew([
            'user_id' => $requestData['user_id'],
            'name' => $requestData['service_name'] . ' Subscription',
            'service_id' => $requestData['service_id'],
            'currency_id' => $requestData['currency_id'],
            'amount' => $requestData['variation_amount'],
            'start_date' => now()->toDateTimeString(),
            'end_date' => today()->addMonth(), // Todo: Find a better way of determining the end date
            'description' => $requestData['variation_code'] . ' Subscription via Subsync',
            'category_id' => $category_id,
        ]);

        $state->setSubscription($subscription);

        Log::info("Subscription tracked successfully");

        return $next($state);
    }
}
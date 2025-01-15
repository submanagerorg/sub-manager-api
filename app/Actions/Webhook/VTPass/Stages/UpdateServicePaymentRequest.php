<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\ServicePaymentRequest;
use Closure;
use Illuminate\Support\Facades\Log;

class UpdateServicePaymentRequest
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        $requestData = $state->getRequestData();

        if (!isset($requestData['data']) || !isset($requestData['data']['requestId'])) {
            Log::info("Request data is incomplete, no request data property sent");
            return;
        };

        if (!isset($requestData['type']) || $requestData['type'] !== 'transaction-update') {
            Log::info("Request data is incomplete, type is not set or not transaction update");

            return;
        }

        $paymentStatus = match($requestData['data']['content']['transactions']['status'] ?? null) {
            'delivered' => ServicePaymentRequest::STATUS['SUCCESSFUL'],
            default => ServicePaymentRequest::STATUS['FAILED']
        };

        ServicePaymentRequest::where('request_id', $requestData['data']['requestId'])
            ->update([
                'status' => $paymentStatus
            ]);
        
        $state->setTransactionStatus($paymentStatus);

        Log::info("Transaction is updated to $paymentStatus");

        return $next($state);
    }
}
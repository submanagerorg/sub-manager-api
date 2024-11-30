<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\ServicePaymentRequest;

class UpdateServicePaymentRequest
{
    public function handle(HandleVTPassWebhookState $state)
    {
        $requestData = $state->getRequestData();

        if (!isset($requestData['data']) || !isset($requestData['data']['requestId'])) return;

        if (!isset($requestData['type']) || $requestData['type'] !== 'transaction-update') return;

        $paymentStatus = match($requestData['data']['content']['transactions']['status'] ?? null) {
            'delivered' => ServicePaymentRequest::STATUS['SUCCESSFUL'],
            default => ServicePaymentRequest::STATUS['FAILED']
        };

        ServicePaymentRequest::where('request_id', $requestData['data']['requestId'])
            ->update([
                'status' => $paymentStatus
            ]);
        
        $state->setTransactionStatus($paymentStatus);
    }
}
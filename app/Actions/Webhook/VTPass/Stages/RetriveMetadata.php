<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\ServicePaymentRequest;

class RetrieveMetadata 
{
    public function handle(HandleVTPassWebhookState $state)
    {
        $requestData = $state->getRequestData();

        $servicePayment = ServicePaymentRequest::where('request_id', $requestData['data']['requestId'])->first();

        $state->setMetaData($servicePayment->request_data);
    }
}
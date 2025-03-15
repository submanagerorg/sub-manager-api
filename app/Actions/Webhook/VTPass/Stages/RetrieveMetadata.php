<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\ServicePaymentRequest;
use Closure;
use Illuminate\Support\Facades\Log;

class RetrieveMetadata 
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        $requestData = $state->getRequestData();

        $servicePayment = ServicePaymentRequest::where('request_id', $requestData['data']['requestId'])->first();

        if (!$servicePayment) {
            Log::info("Service payment record for this transaction not found", [
                'request_id' => $requestData['data']['requestId']
            ]);

            return;
        }

        $state->setMetaData($servicePayment->request_data);

        Log::info("Metadata retieved", [$servicePayment->request_data]);

        return $next($state);
    }
}
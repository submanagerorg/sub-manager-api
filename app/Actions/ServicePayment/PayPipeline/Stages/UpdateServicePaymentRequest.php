<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Models\ServicePaymentRequest;
use Closure;
use Illuminate\Support\Facades\Log;

class UpdateServicePaymentRequest
{
    public function handle(HandlePaymentState $state, Closure $next)
    {
        $payResponse = $state->getParsedPayResponse();
        $servicePaymentRequest = ServicePaymentRequest::whereRequestId($payResponse->requestId)->first();

        $paymentStatus = match($payResponse->status) {
            'delivered' => ServicePaymentRequest::STATUS['SUCCESSFUL'],
            default => ServicePaymentRequest::STATUS['FAILED']
        };

        $servicePaymentRequest->update([
            'status' => $paymentStatus,
        ]);

        $state->setTransactionStatus($paymentStatus);

        Log::info("Service payment request updated successfully");

        return $next($state);
    }
}
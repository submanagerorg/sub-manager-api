<?php

namespace App\Actions\ServicePayment\PayPipeline;

use App\Dto\PayResponseDto;
use App\Models\ServicePaymentRequest;
use App\Models\Subscription;

class HandlePaymentState
{
    protected $subscription;
    protected $transactionStatus;
    protected $parsedPayResponse;
    
    public function __construct(public readonly array $requestData, public readonly mixed $payResponse)
    {
        
    }

    public function getPayResponse() {
        return $this->payResponse;
    }

    public function getParsedPayResponse(): PayResponseDto {
        return $this->parsedPayResponse;
    }

    public function setParsedPayResponse(PayResponseDto $parsedPayResponse) {
        $this->parsedPayResponse = $parsedPayResponse;
    }

    public function getRequestData() {
        return $this->requestData;
    }

    public function getSubcription() {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription) {
        $this->subscription = $subscription;
    }

    public function setTransactionStatus(string $status) {
        $this->transactionStatus = $status;
    }

    public function transactionSuccessful() {
        return $this->transactionStatus === ServicePaymentRequest::STATUS['SUCCESSFUL'];
    }

    public function transactionFailed() {
        return $this->transactionStatus !== ServicePaymentRequest::STATUS['SUCCESSFUL'];
    }
}
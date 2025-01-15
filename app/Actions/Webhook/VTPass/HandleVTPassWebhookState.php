<?php 

namespace App\Actions\Webhook\VTPass;

use App\Models\ServicePaymentRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class HandleVTPassWebhookState
{
    protected $requestData;
    protected $metaData;
    protected $subscription;
    protected $transactionStatus;
    
    public function __construct(public readonly Request $request)
    {
        
    }

    public function getRequestData() {
        return $this->requestData;
    }

    public function setRequestData($requestData) {
        $this->requestData = $requestData;
    }

    public function setMetaData($metaData) {
        $this->metaData = $metaData;
    }

    public function getMetaData() {
        return $this->metaData;
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
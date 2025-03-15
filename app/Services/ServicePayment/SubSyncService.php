<?php

namespace App\Services\ServicePayment;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class SubSyncService
{
    public function getUniqueRequiredFields(): mixed
    {
        return [];
    }

    public function getSmartCardDetails($cardNumber): mixed 
    {
        return [];
    }

    public function getVariations(): mixed 
    {
        $this->setPayForServiceDriver();
    
        return PayForService::getSubSyncVariations();
    }

    public function pay($data): mixed 
    {
        $this->setPayForServiceDriver();

        return PayForService::paySubSync($data);
    }

    public function generateRequestId(): mixed 
    {
        $this->setPayForServiceDriver();

        return PayForService::generateRequestId();
    }

    public function getCurrencyCode(): string 
    {
        return 'NGN';
    }

    private function setPayForServiceDriver(): void 
    {
        config(['payforservice.default_driver' => 'subsync']);
    }
}
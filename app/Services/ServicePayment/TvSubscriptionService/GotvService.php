<?php

namespace App\Services\ServicePayment\TvSubscriptionService;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class GotvService implements TvSubscriptionServiceInterface
{
    public function getUniqueRequiredFields(): mixed
    {
        return [
            [
                'name' => 'smartcard_number',
                'label' => 'SmartCard Number',
                'type' => 'text',
                'required' => true,
            ]
        ];
    }

    public function getSmartCardDetails($cardNumber): mixed 
    {
        return PayForService::getGotvSmartCardDetails($cardNumber);
    }

    public function getVariations(): mixed 
    {
        return PayForService::getGotvVariations();
    }

    public function pay($data): mixed 
    {
        return PayForService::payGotv($data);
    }

    public function generateRequestId(): mixed 
    {
        return PayForService::generateRequestId();
    }
    
    public function getCurrencyCode(): string 
    {
        return 'NGN';
    }
}
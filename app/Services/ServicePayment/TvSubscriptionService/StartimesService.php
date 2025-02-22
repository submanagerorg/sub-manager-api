<?php

namespace App\Services\ServicePayment\TvSubscriptionService;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class StartimesService implements TvSubscriptionServiceInterface
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
        return PayForService::getStartimesSmartCardDetails($cardNumber);
    }

    public function getVariations(): mixed 
    {
        return PayForService::getStartimesVariations();
    }

    public function pay($data): mixed 
    {
        return PayForService::payStartimes($data);
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
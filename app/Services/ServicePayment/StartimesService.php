<?php

namespace App\Services\ServicePayment;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class StartimesService implements TvSubscriptionServiceInterface
{
    public function getUniqueRequiredFields(): mixed
    {
        return [
            'smartcard_number',
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
   
}
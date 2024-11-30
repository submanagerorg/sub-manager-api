<?php

namespace App\Services\ServicePayment;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class ShowmaxService implements TvSubscriptionServiceInterface
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
        return PayForService::getShowmaxVariations();
    }

    public function pay($data): mixed 
    {
        return PayForService::payShowmax($data);
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

   
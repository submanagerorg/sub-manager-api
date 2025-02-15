<?php

namespace App\Services\ServicePayment;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class DstvService implements TvSubscriptionServiceInterface
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
        return PayForService::getDstvSmartCardDetails($cardNumber);
    }

    public function getVariations(): mixed 
    {
        return PayForService::getDstvVariations();
    }

    public function pay($data): mixed 
    {
        return PayForService::payDstv($data);
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
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
        // return [
        //     [
        //         "variation_code" => "full",
        //         "name" => "Full",
        //         "variation_amount" => "2900.00",
        //         "fixedPrice" => "Yes"
        //     ]
 
        // ];
                
        return PayForService::getShowmaxVariations();
    }

    public function pay(): mixed {
        //return [];

        return PayForService::payShowmax();
    }

    public function generateRequestId(): mixed 
    {
        // return Str::random(10);

        return PayForService::generateRequestId();
    }
   
}

   
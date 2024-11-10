<?php

namespace App\Services\ServicePayment;

use App\Facades\PayForService;
use Illuminate\Support\Str;

class DstvService implements TvSubscriptionServiceInterface
{
    public function getUniqueRequiredFields(): mixed
    {
        return [
            'smartcard_number',
        ];
    }

    public function getSmartCardDetails($cardNumber): mixed 
    {
        // return [
        //     'customer_name' =>  "Mr  DsTEST",
        //     'due_date' => "2019-07-23T00:00:00",
        //     'current_variation_name' =>  "DStv Premium-Asia N17630 + DStv French only N6050 + DStv Premium-French N20780",
        //     'current_variation_code' => "dstv10, dstv5, dstv9",
        //     'renewal_amount' => "3500"
        // ];

        return PayForService::getDstvSmartCardDetails();
    }

    public function getVariations(): mixed 
    {
        // return [
        //     [
        //         "variation_code" => "dstv1",
        //         "name" => "DStv Access N2000",
        //         "variation_amount" => "2000.00",
        //         "fixedPrice" => "Yes"
        //     ], 
        //     [
        //         "variation_code" => "dstv2",
        //         "name" => "DStv Family N4000",
        //         "variation_amount" => "4000.00",
        //         "fixedPrice" => "Yes"
        //     ]
        // ];

        return PayForService::getDstvVariations();
    }

    public function pay(): mixed 
    {
        //return [];

        return PayForService::payDstv();
    }

    public function generateRequestId(): mixed 
    {
        //return Str::random(10);

        return PayForService::generateRequestId();
    }
   
}
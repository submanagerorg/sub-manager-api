<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;

class FlutterwaveDriver implements PayForServiceInterface
{
    public function payDstv(array $paymentData): mixed
    {
        return false;
    }

    public function getWalletBalance(): mixed
    {
        return true;
    }
}
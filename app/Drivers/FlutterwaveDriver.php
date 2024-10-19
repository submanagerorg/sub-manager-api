<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;

class FlutterwaveDriver implements PayForServiceInterface
{
    public function payDstv(): mixed
    {
        return false;
    }
}
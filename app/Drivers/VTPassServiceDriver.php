<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;

class VTPassServiceDriver implements PayForServiceInterface
{
    public function payDstv(): mixed
    {
        return true;
    }
}
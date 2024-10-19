<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PayForService extends Facade
{
    /**
    * Get the registered name of the component.
    * @return string
    *
    * @throws \RuntimeException
    */
    protected static function getFacadeAccessor()
    {
        return 'payforservice';
    }
}
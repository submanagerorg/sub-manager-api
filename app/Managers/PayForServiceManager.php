<?php 

namespace App\Managers;

use App\Drivers\FlutterwaveDriver;
use App\Drivers\SubSyncServiceDriver;
use App\Drivers\VTPassServiceDriver;
use Illuminate\Support\Manager;

class PayForServiceManager extends Manager
{
    public function getDefaultDriver()
    {
        return config('payforservice.default_driver') ?? 'vtpass';
    }

    public function createVtpassDriver()
    {
        return new VTPassServiceDriver();
    }

    public function createFlutterwaveDriver()
    {
        return new FlutterwaveDriver();
    }

    public function createSubSyncDriver()
    {
        return new SubSyncServiceDriver();
    }
}
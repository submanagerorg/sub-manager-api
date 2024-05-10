<?php

namespace App\Http\Controllers;

use App\Actions\Service\GetServicesAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ServiceController extends Controller
{
    public function getServices(Request $request)
    {
        return (new GetServicesAction())->execute($request->all());
    }
}

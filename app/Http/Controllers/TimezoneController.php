<?php

namespace App\Http\Controllers;

use App\Actions\Timezone\GetTimezonesAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TimezoneController extends Controller
{
    public function getTimezones(Request $request)
    {
        return (new GetTimezonesAction())->execute($request->all());
    }
}

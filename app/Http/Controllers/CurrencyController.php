<?php

namespace App\Http\Controllers;

use App\Actions\Currency\GetCurrenciesAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CurrencyController extends Controller
{
    public function getCurrencies(Request $request)
    {
        return (new GetCurrenciesAction())->execute($request->all());
    }
}

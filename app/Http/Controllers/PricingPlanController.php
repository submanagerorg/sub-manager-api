<?php

namespace App\Http\Controllers;

use App\Actions\PricingPlan\GetPricingPlansAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PricingPlanController extends Controller
{
    public function getPricingPlans(Request $request)
    {
        return (new GetPricingPlansAction())->execute($request->all());
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\PlanPayment\InitiatePaymentAction;
use App\Actions\PlanPayment\ProcessWebhookAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanPayment\InitiatePaymentRequest;

class PlanPaymentController extends Controller
{
      
    public function initiatePayment(InitiatePaymentRequest $request)
    {
        return (new InitiatePaymentAction())->execute($request->validated());
    }

    public function processWebhook(Request $request)
    {
        return (new ProcessWebhookAction())->execute($request);
    }
    
}

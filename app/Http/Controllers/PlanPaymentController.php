<?php

namespace App\Http\Controllers;

use App\Actions\PlanPayment\InitiatePaymentAction;
use App\Actions\PlanPayment\ProcessCallbackAction;
use App\Actions\PlanPayment\ProcessWebhookAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanPayment\InitiatePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PlanPaymentController extends Controller
{
      
    public function initiatePayment(InitiatePaymentRequest $request): JsonResponse
    {
        return (new InitiatePaymentAction())->execute($request->validated());
    }

    public function processCallback(Request $request): RedirectResponse
    {
        return (new ProcessCallbackAction())->execute($request);
    }

    public function processWebhook(Request $request): JsonResponse
    {
        return (new ProcessWebhookAction())->execute($request);
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Actions\ServicePayment\GetValidationFieldsAction;
use App\Actions\ServicePayment\GetVariationsAction;
use App\Actions\ServicePayment\PayAction;
use App\Actions\ServicePayment\VerifySmartCardNumberAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePayment\GenericServiceRequest;
use App\Http\Requests\ServicePayment\PayRequest;
use App\Http\Requests\ServicePayment\VerifySmartCardNumberRequest;
use Illuminate\Http\JsonResponse;

class ServicePaymentController extends Controller
{
    public function getValidationFields(GenericServiceRequest $request)
    {
        return (new GetValidationFieldsAction())->execute($request->all());
    }

    public function verifySmartCardNumber(VerifySmartCardNumberRequest $request)
    {
        return (new VerifySmartCardNumberAction())->execute( $request->all());
    }

    public function getVariations(GenericServiceRequest $request)
    {
        return (new GetVariationsAction())->execute($request->all());
    }

    public function pay(PayRequest $request)
    {
        return (new PayAction())->execute($request->user(), $request->all());
    }
}

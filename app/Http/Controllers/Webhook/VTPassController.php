<?php

namespace App\Http\Controllers\Webhook;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VTPassController
{
    public function handleWebhook(Request $request)
    {
        (new HandleVTPassWebhookAction())->execute($request);

        return response()->json([
            "response" => "success"
        ], Response::HTTP_OK);
    }
}
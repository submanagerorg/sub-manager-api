<?php

namespace App\Http\Controllers\Webhook;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookAction;
use App\Models\AutoRenewal;
use App\Models\Currency;
use App\Models\ServicePaymentRequest;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class VTPassController
{
    protected $metaData;
    protected $requestData;

    public function __construct()
    {
        
    }

    public function handleWebhook(Request $request)
    {
        (new HandleVTPassWebhookAction())->execute($request);
    }
}
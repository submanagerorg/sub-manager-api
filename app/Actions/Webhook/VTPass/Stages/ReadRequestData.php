<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use Closure;
use Illuminate\Support\Facades\Log;

class ReadRequestData
{
    public function handle(HandleVTPassWebhookState $state, Closure $next) {
        $requestData = $state->request->json()->all();
        // $requestData = json_decode($requestData, true);

        $state->setRequestData($requestData);

        Log::info("Reading request data...", [$requestData]);

        return $next($state);
    }
}
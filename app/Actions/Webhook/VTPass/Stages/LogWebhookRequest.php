<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\WebhookRequestLog;
use Closure;
use Illuminate\Support\Facades\Log;

class LogWebhookRequest 
{
    public function handle(HandleVTPassWebhookState $state, Closure $next) {
        WebhookRequestLog::create(
            [
                'data' => json_encode($state->request->all()),
                'ip_address' => $state->request->ip(),
                'request_url' => $state->request->url()
            ]
        );

        Log::info("Webhook request logged succesfully");

        return $next($state);
    }
}
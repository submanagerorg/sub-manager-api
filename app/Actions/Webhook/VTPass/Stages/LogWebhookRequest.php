<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use Illuminate\Support\Facades\DB;

class LogWebhookRequest 
{
    public function handle(HandleVTPassWebhookState $state) {
        DB::table('webhook_request_log')->create(
            [
                'data' => json_encode($state->request->all()),
                'ip_address' => $state->request->ip(),
                'request_url' => $state->request->url()
            ]
        );
    }
}
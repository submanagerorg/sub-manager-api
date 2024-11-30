<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;

class ReadRequestData
{
    public function handle(HandleVTPassWebhookState $state) {
        $requestData = $state->request->json();
        if (!is_array($requestData)) {
            $requestData = json_decode($requestData, true);
        }

        $state->setRequestData($requestData);
    }
}
<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use Illuminate\Support\Facades\Cache;

class UpdateCacheForVariations
{
    public function handle(HandleVTPassWebhookState $state)
    {
        $requestData = $state->getRequestData();

        if (!isset($requestData['type']) || $requestData['type'] !== 'variations-update') return;

        if (!isset($requestData['data']['content']['variations'])) return;

        if (!isset($requestData['serviceID'])) return;

        Cache::rememberForever($requestData['serviceID'].'-variations', function () use ($requestData) {
            return $requestData['data']['content']['variations'];
        });
    }
}
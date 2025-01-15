<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateCacheForVariations
{
    public function handle(HandleVTPassWebhookState $state, Closure $next)
    {
        $requestData = $state->getRequestData();

        if (!isset($requestData['type']) || $requestData['type'] !== 'variations-update') {
            Log::info("Type is not set, or type is not variation update");

            return $next($state);
        };

        if (!isset($requestData['data']['content']['variations'])) {
            Log::info("Variations property is not set");

            return $next($state);
        };

        if (!isset($requestData['serviceID'])) {
            Log::info("Service ID is missing");

            return $next($state);
        };

        Cache::rememberForever($requestData['serviceID'].'-variations', function () use ($requestData) {
            return $requestData['data']['content']['variations'];
        });

        Log::info("Variations cache updated successfully");

        return;
    }
}
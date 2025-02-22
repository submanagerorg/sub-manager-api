<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;
use App\Models\PricingPlan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SubSyncServiceDriver
{
    public function paySubSync(array $paymentData): mixed
    {
        $paymentResponse = [
            'content' => [
                'transactions' => [
                    'status' => 'success',
                ],
            ],
            'requestId' => $paymentData['request_id'] ?? $this->generateRequestId(),
        ];

        return $paymentResponse;
    }

    public function getSubSyncVariations(): mixed 
    {
        $variations = PricingPlan::where('name', '!=', 'basic')->get();

        $formattedVariations = $variations->map(function ($item) {
            return [
                'variation_code' => strtolower("{$item['name']}-{$item['period']}"),
                'name' => "{$item['name']} ({$item['period']})",
                'amount' => $item['amount']
            ];
        })->toArray();

        return $formattedVariations;
    }

    public function generateRequestId(): string 
    {
        $now = now();
        $now->setTimezone('Africa/Lagos');

        return $now->format('YmdHi') . Str::random(5) . 'subsync';
    }
}
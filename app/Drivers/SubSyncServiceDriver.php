<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;
use App\Models\PricingPlan;
use App\Models\User;
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
        $user =  User::where('id', $paymentData['user_id'])->first();
        
        [$name, $period] = explode('-', $paymentData['variation_code']);
        $pricingPlan = PricingPlan::where('name', $name)->where('period', $period)->first();

        $user->addUserPricingPlan($pricingPlan);
        
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
                'amount' => $item['amount'],
                'period' => $item['period']
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
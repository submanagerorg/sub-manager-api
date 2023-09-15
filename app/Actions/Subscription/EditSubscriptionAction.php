<?php
namespace App\Actions\Subscription;

use App\Models\Currency;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;


class EditSubscriptionAction
{
    use FormatApiResponse;
    
   /**
    *
    * @param string $subscriptionId
    * @param array $data
    * @return JsonResponse
    */
    public function execute($subscriptionId, array $data)
    {
        $user = auth()->user();

        $subscription =  Subscription::where('uid', $subscriptionId)->where('user_id', $user->id)->first();

        if(!$subscription){
            return $this->formatApiResponse(400, 'Subscription does not exist for user');
        }

        $data = [
            'name' => $data['name'] ?? $subscription->name,
            'url' => $data['url'] ?? $subscription->url,
            'currency_id' => $data['currency_id'] ?? Currency::where('symbol', $data['currency'])->first()->id,
            'amount' => $data['amount'] ?? $subscription->amount,
            'start_date' => $data['start_date'] ?? $subscription->start_date,
            'end_date' => $data['end_date'] ?? $subscription->end_date,
            'description' => $data['description'] ?? $subscription->description,
        ];

        $subscription->update($data);

        return $this->formatApiResponse(200, 'Subscription has been updated', $subscription);
    }
}
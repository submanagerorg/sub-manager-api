<?php
namespace App\Actions\Subscription;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Service;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;
use Illuminate\Support\Str;


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

        if($subscription->status !== Subscription::STATUS['ACTIVE']){
            return $this->formatApiResponse(400, 'Subscription is no longer active');
        }

        $data = [
            'currency_id' =>  isset($data['currency']) ? Currency::where('code', $data['currency'])->first()->id : $subscription->currency_id,
            'amount' =>  isset($data['amount']) ? $data['amount'] : $subscription->amount,
            'start_date' =>  isset($data['start_date']) ? $data['start_date'] : $subscription->start_date,
            'end_date' =>  isset($data['end_date']) ? $data['end_date'] : $subscription->end_date,
            'description' =>  isset($data['description']) ? $data['description'] : $subscription->description,
        ];

        $subscription->update($data);

        $subscription->refresh();

        return $this->formatApiResponse(200, 'Subscription has been updated', $subscription);
    }
}
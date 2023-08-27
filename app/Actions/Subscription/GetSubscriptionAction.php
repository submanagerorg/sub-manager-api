<?php
namespace App\Actions\Subscription;

use App\Models\Subscription;
use App\Traits\FormatApiResponse;


class GetSubscriptionAction
{
    use FormatApiResponse;
    
   /**
    *
    * @param string $subscriptionId
    * @return JsonResponse
    */
    public function execute(string $subscriptionId)
    {
        $user = auth()->user();

        $subscription =  Subscription::where('uid', $subscriptionId)->where('user_id', $user->id)->first();

        if(!$subscription){
            return $this->formatApiResponse(400, 'Subscription does not exist for user');
        }

        return $this->formatApiResponse(200, 'Subscription retrieved successfully', $subscription);
    }
}
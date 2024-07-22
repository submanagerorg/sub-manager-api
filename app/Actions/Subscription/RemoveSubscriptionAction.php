<?php
namespace App\Actions\Subscription;

use App\Models\Subscription;
use App\Traits\FormatApiResponse;


class RemoveSubscriptionAction
{
    use FormatApiResponse;
    
   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute($subscriptionId)
    {
        $user = auth()->user();

        $subscription =  Subscription::where('uid', $subscriptionId)->where('user_id', $user->id)->first();

        if(!$subscription){
            return $this->formatApiResponse(400, 'Subscription does not exist for user');
        }

        if($subscription->status !== Subscription::STATUS['ACTIVE']){
            return $this->formatApiResponse(400, 'Subscription is no longer active');
        }
        
        $subscription->delete();

        return $this->formatApiResponse(200, 'Subscription has been removed');
    }
}
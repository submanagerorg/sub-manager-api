<?php
namespace App\Actions\Subscription;

use App\Http\Filters\SubscriptionFilter;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;


class GetSubscriptionsAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(SubscriptionFilter $filter, array $data)
    {
        $user = auth()->user();

        $subscriptions =  Subscription::where('user_id', $user->id);

        $subscriptions = $subscriptions->filter($filter)->paginate($data['length'] ?? 20);

        return $this->formatApiResponse(200, 'Subscriptions retrieved successfuly', $subscriptions);
    }
}
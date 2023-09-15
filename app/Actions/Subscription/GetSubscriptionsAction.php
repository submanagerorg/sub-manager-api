<?php
namespace App\Actions\Subscription;

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
    public function execute(array $data)
    {
        $user = auth()->user();

        $subscriptions =  Subscription::where('user_id', $user->id);

        $subscriptions = $subscriptions->paginate($data['length'] ?? 10);

        return $this->formatApiResponse(200, 'Subscriptions retrieved successfuly', $subscriptions);
    }
}
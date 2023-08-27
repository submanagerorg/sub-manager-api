<?php
namespace App\Actions\Subscription;

use App\Models\Subscription;
use App\Traits\FormatApiResponse;

class AddSubscriptionAction
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
         $data['user_id'] = $user->id;

        if(Subscription::exists($data)){
            return $this->formatApiResponse(400, 'Subscription already exists');
        }

        $subscription = Subscription::createNew($data);

        return $this->formatApiResponse(200, 'Subscription has been added', $subscription);
    }
}
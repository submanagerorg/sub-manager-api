<?php
namespace App\Actions\Subscription;

use App\Models\Currency;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class RenewSubscriptionAction
{
    use FormatApiResponse;

   /**
    * @param string $parentId
    * @param array $data
    * @return JsonResponse
    */
    public function execute($parentId, array $data): JsonResponse
    {
        try{
            $user = auth()->user();

            $parentSubscription = Subscription::where('uid', $parentId)->first();

            if(!$parentSubscription){
                return $this->formatApiResponse(400, 'Parent subscription does not exist');
            }
    
            $data['user_id'] = $user->id;
            $data['currency_id'] = Currency::where('code', $data['currency'])->first()->id;
            $data['parent_id'] = $parentSubscription->id;
            $data['name'] = $parentSubscription->name;
            $data['url'] = $parentSubscription->url;
            $data['category_id'] = $parentSubscription->category_id;
    
            $subscription = Subscription::createNew($data);
    
            if(!$subscription){
                return $this->formatApiResponse(400, 'Subscription already exists');
            }
    
            return $this->formatApiResponse(200, 'Subscription has been renewed', $subscription);

        } catch(Throwable $e) {
            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}
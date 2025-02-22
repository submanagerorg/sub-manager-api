<?php
namespace App\Actions\Subscription;

use App\Models\Currency;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class RenewSubscriptionAction
{
    use FormatApiResponse;

   /**
    * @param string $parentId
    * @param array $data
    * @param bool $isSubSyncSubscription
    * @return JsonResponse
    */
    public function execute(string $parentId, array $data, bool $isSubSyncSubscription = false): JsonResponse
    {
        try{
            if($isSubSyncSubscription) {
                $user = User::where('email', $data['email'])->first();
            } else {
                $user = auth()->user();
            }

            if($user->isRenewalLimitReached()){
                return $this->formatApiResponse(400, 'Renewal limit exceeded. Upgrade to higher plan.');
            }

            $parentSubscription = Subscription::where('uid', $parentId)->where('user_id', $user->id)->first();

            if(!$parentSubscription){
                return $this->formatApiResponse(400, 'Parent subscription does not exist');
            }

            $data['user_id'] = $user->id;
            $data['currency_id'] = Currency::where('code', $data['currency'])->first()->id;
            $data['parent_id'] = $parentSubscription->id;
            $data['name'] = $parentSubscription->name;
            $data['url'] = $parentSubscription->url;
            $data['category_id'] = $parentSubscription->category_id;
            $data['service_id'] = $parentSubscription->service_id;
            $data['description'] =  $data['description'] ?? $parentSubscription->description;
    
            $subscription = Subscription::createNew($data);

            if(!$subscription){
                return $this->formatApiResponse(400, 'Subscription already exists');
            }

            $parentSubscription->update([
                'status' =>  Subscription::STATUS['EXPIRED']
            ]);
    
            return $this->formatApiResponse(200, 'Subscription has been renewed', $subscription);

        } catch(Throwable $e) {
            if($isSubSyncSubscription){
                throw new \Exception($e);
            }

            report($e);

            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}
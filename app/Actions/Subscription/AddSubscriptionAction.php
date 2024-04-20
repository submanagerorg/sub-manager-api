<?php
namespace App\Actions\Subscription;

use App\Models\Currency;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;
use Throwable;

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
        try{
            $user = auth()->user();

            if($user->isSubscriptionLimitReached()){
                return $this->formatApiResponse(400, 'Subscription limit exceeded. Upgrade to higher plan.');
            }
    
            $data['user_id'] = $user->id;
            $data['currency_id'] = Currency::where('code', $data['currency'])->first()->id;
    
            $subscription = Subscription::createNew($data);
    
            if(!$subscription){
                return $this->formatApiResponse(400, 'Subscription already exists');
            }
    
            return $this->formatApiResponse(200, 'Subscription has been added', $subscription);

        } catch(Throwable $e) {
            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}
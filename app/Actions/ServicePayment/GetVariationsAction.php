<?php
namespace App\Actions\ServicePayment;

use App\Models\Service;
use App\Traits\FormatApiResponse;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GetVariationsAction
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
            $response = Cache::rememberForever($data['service_name']. '-variations', function () use ($data){
                
                $variations = Service::getServiceClass($data['service_name'])->getVariations();

                $fee = config('fee.tv_service');

                $updatedData = collect($variations)->map(function ($item) use ($fee) {
                    $amount = (float) $item['amount'];
                    $item['fee'] =  sprintf('%.2f', $fee);
                    $item['total'] = sprintf('%.2f', $amount + $fee);
                    
                    return $item;
                });

                $updatedData = $updatedData->toArray();

                return $updatedData;
            });

            return $this->formatApiResponse(200, 'Service variations retrieved successfully.', $response);

        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to retrieve service variations.');
        }   
    }
}
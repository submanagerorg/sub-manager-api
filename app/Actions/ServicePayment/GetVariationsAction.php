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
                return Service::getServiceClass($data['service_name'])->getVariations();
            });

            $fee = config('fee.tv_service');

            $data = collect($response)->map(function ($item) use ($fee) {
                $item['fee'] =  sprintf('%.2f', $fee);
                $item['total'] = sprintf('%.2f', $item['amount'] + $fee);
                
                return $item;
            });

            $data = $data->toArray();

            return $this->formatApiResponse(200, 'Service variations retrieved successfully.', $data);

        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to retrieve service variations.');
        }   
    }
}
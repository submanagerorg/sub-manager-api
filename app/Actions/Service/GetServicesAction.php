<?php
namespace App\Actions\Service;

use App\Models\Service;
use App\Traits\FormatApiResponse;


class GetServicesAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {

        $services = Service::query();
        
        if (isset($data['payment_supported'])) {
            $paymentSupported = filter_var($data['payment_supported'], FILTER_VALIDATE_BOOLEAN);
            $services->where('is_payment_supported', (bool)$paymentSupported );
        }
        
        $services->orderBy('name', 'asc')->get();

        return $this->formatApiResponse(200, 'Services retrieved successfuly', $services->get());
    }
}
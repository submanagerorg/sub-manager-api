<?php
namespace App\Actions\PricingPlan;

use App\Models\PricingPlan;
use App\Traits\FormatApiResponse;


class GetPricingPlansAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        $pricingPlans = PricingPlan::orderBy('name', 'asc')->get();

        return $this->formatApiResponse(200, 'Pricing Plans retrieved successfuly', $pricingPlans);
    }
}
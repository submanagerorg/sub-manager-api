<?php
namespace App\Actions\PricingPlan;

use App\Models\PricingPlan;
use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;

class GetPricingPlanAction
{
    use FormatApiResponse;

   /**
    *
    * @param string $pricingPlanId
    * @return JsonResponse
    */
    public function execute(string $pricingPlanId): JsonResponse
    {
        $pricingPlan = PricingPlan::where('uid', $pricingPlanId)->first();

        if (!$pricingPlan) {
            return $this->formatApiResponse(404, "Pricing plan not found");
        }

        return $this->formatApiResponse(200, 'Pricing Plans retrieved successfuly', $pricingPlan);
    }
}

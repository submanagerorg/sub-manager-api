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

        $pricingPlans = $this->groupPlansByName($pricingPlans->toArray());

        return $this->formatApiResponse(200, 'Pricing Plans retrieved successfuly', $pricingPlans);
    }

    private function groupPlansByName($plans) 
    {
        $grouped_plans = [];

        foreach ($plans as $plan) {
            $name = $plan['name'];
            if (!isset($grouped_plans[$name])) {
                $grouped_plans[$name] = [
                    "name" => $plan['name'],
                    "details" => $plan['details'],
                    "subscription_limit" => $plan['subscription_limit']
                ];
            }
            $period = $plan['period'];
            $grouped_plans[$name][$period] = [
                "uid" => $plan['uid'],
                "period" => $plan['period'],
                "amount" => $plan['amount'],
                "on_discount" => $plan['on_discount'],
                "discount_amount" => $plan['discount_amount'],
                "created_at" => $plan['created_at'],
                "updated_at" => $plan['updated_at']
            ];
        }
        return $grouped_plans;
    }

}

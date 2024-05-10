<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get("database/seeders/data/pricing_plans.json"));
        
        foreach($data as $datum){
            $pricingPlan = PricingPlan::where('name', $datum->name)->where('period', $datum->period)->first();

            if (!$pricingPlan) {
                $pricingPlan = new PricingPlan();
                $pricingPlan->uid = Str::orderedUuid();
            }   

            $pricingPlan->name = $datum->name;
            $pricingPlan->details = json_encode($datum->details);
            $pricingPlan->subscription_limit = $datum->subscription_limit;
            $pricingPlan->amount = $datum->amount;
            $pricingPlan->period = $datum->period;
            $pricingPlan->save();
        }
       
    }
}

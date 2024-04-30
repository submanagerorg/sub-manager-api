<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\PricingPlan;
use App\Models\PricingPlanFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PricingPlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get("database/seeders/data/pricing_plan_features.json"));
        
        foreach($data as $datum){
            $pricingPlan = PricingPlan::where('name', $datum->plan)->first();

            if (!$pricingPlan) {
               continue;
            } 
            
            $feature = Feature::where('name', $datum->feature)->first();

            if (!$feature) {
               continue;
            } 

            $pricingPlanFeature = new PricingPlanFeature();
            $pricingPlanFeature->pricing_plan_id = $pricingPlan->id;
            $pricingPlanFeature->feature_id = $feature->id;
            $pricingPlanFeature->save();
        }
       
    }
}

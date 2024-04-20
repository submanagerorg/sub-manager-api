<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPricingPlanHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

     /**
     * @param array $data
     * @return self|null
     * 
     */
    public static function createNew(array $data): self | null
    {
        $userPricingPlanHistory = self::create([
            'user_id' => $data['userPricingPlan']->user_id,
            'pricing_plan_id'=> $data['userPricingPlan']->pricing_plan_id,
            'amount' =>  $data['userPricingPlan']->amount,
            'start_date' =>  $data['userPricingPlan']->start_date,
            'end_date' =>  $data['userPricingPlan']->end_date
        ]);

        return $userPricingPlanHistory;
    }
}

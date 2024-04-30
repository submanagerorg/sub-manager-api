<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PricingPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'laravel_through_key'
    ];

    protected $casts = [
        'on_discount' => 'boolean',
        'features' => 'array'
    ];

    public const PERIOD = [
        'LIFETIME' => 'lifetime',
        'MONTHLY' => 'monthly',
        'YEARLY' => 'yearly',
    ];

    public const PLANS = [
        'BASIC' => 'basic',
        'STANDARD' => 'standard',
        'PREMIUM' => 'premium'
    ];

    // protected $with = ['features'];

    /**
     * returns user pricing plan
     *
     * @return HasMany
     */
    public function userPricingPlans(): HasMany
    {
        return $this->hasMany(UserPricingPlan::class, 'pricing_plan_id');
    }

    /**
     * returns pricing plan feature
     *
     * @return HasMany
     */
    public function pricingPlanFeatures(): HasMany
    {
        return $this->hasMany(PricingPlanFeature::class, 'pricing_plan_id');
    }

     /**
     * returns feature
     *
     * @return HasManyThrough
     */
    public function features(): HasManyThrough
    { 
        return $this->hasManyThrough(Feature::class, PricingPlanFeature::class, 'pricing_plan_id', 'id', 'id', 'feature_id');
    }


    /**
     * Calculates end date using start date and period
     * 
     * @param $startDate
     * @param $period
     * 
     * @return object|null 
     */
    public static function getEndDate($startDate, $period)
    {
        if($period == PricingPlan::PERIOD['MONTHLY']){
            return $startDate->addMonth();
        }
        if($period == PricingPlan::PERIOD['YEARLY']){
            return $startDate->addYear();
        }

        return null;
    }

}

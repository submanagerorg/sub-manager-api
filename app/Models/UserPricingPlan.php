<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPricingPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

     /**
     * Returns the user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     /**
     * Returns the pricing plan
     *
     * @return BelongsTo
     */
    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_id');
    }

    /**
     * @param array $data
     * @return self|null
     * 
     */
    public static function createNew(array $data): self | null
    {
        $userPricingPlan = self::create([
            'user_id' => $data['user_id'],
            'pricing_plan_id'=>  $data['pricing_plan']->id,
            'amount'=>  $data['pricing_plan']->on_discount ? $data['pricing_plan']->discount_amount :  $data['pricing_plan']->amount,
            'start_date'=> now(),
            'end_date'=> PricingPlan::getEndDate(now(),  $data['pricing_plan']->period)
        ]);

        return $userPricingPlan;
    }

    /**
     * @param array $data
     * @return self|null
     * 
     */
    public function updateRecord(array $data): self | null
    {
        $this->update([
            'pricing_plan_id' => $data['pricing_plan']->id,
            'amount' => $data['pricing_plan']->on_discount ? $data['pricing_plan']->discount_amount : $data['pricing_plan']->amount,
            'start_date' => now(),
            'end_date' => PricingPlan::getEndDate(now(), $data['pricing_plan']->period)
        ]);

        return $this;
    }
}

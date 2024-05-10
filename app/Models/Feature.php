<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
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

    /**
     * returns pricing plan features
     *
     * @return HasMany
     */
    public function pricingPlanFeatures(): HasMany
    {
        return $this->hasMany(PricingPlanFeature::class, 'feature_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timezone extends Model
{
    use HasFactory;

    const DEFAULT_TIMEZONE = 'Africa/Lagos';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

     /**
     * returns user timezone
     *
     * @return HasOne
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'timezone_id');
    }
}

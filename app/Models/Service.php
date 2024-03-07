<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'category_id'
    ];

    public const STATUS = [
        'APPROVED' => 'approved',
        'PENDING' => 'pending',
        'REJECTED' => 'rejected',
    ];

    protected $appends = ['category'];

    /**
     * Returns the category of the service.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(User::class, 'category_id');
    }

    /**
     * Get Category Attribute.
     *
     * @param $value
     * @return string
     */
    public function getCategoryAttribute()
    {
       return Category::where('id', $this->category_id)->first()->name;
    }
}

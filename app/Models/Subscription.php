<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory, Filterable;

    protected $guarded = ['id'];

    public const STATUS = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'currency_id', 'user_id', 'category_id'
    ];

    protected $with = ['category'];

    protected $appends = ['user_uid', 'currency'];

    /**
     * Returns the user of the subscription.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     /**
     * Returns the currency of the subscription.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Returns the category of the subscription.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get Currency Attribute.
     *
     * @return string
     */
    public function getCurrencyAttribute()
    {
       return Currency::where('id', $this->currency_id)->first()->code;
    }

    /**
     * Get User Attribute.
     *
     * @return string
     */
    public function getUserUidAttribute()
    {
       return User::where('id', $this->user_id)->first()->uid;
    }

    /**
     * @param array $data
     * @return self|null
     */
    public static function createNew(array $data): self | null
    { 
        if(self::exists($data)){
            return null;
        }

        $service = Service::where('name', 'like', '%' . $data['name'] . '%')->first();

        if ($service) {
            $category = $service->category;
        }else{
            $category = Service::categorize($data);
        }
        
        $subscription = self::create([
            'uid' => Str::orderedUuid(),
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'url' => isset($data['url']) ? $data['url'] : null,
            'currency_id' => $data['currency_id'],
            'category_id' => $category->id,
            'amount' => $data['amount'],
            'status' => self::STATUS['ACTIVE'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => isset($data['description']) ? $data['description'] : null,
        ]);

        return $subscription->load('category');
    }

     /**
     * @param array $data
     * @return bool
     */
    public static function exists(array $data): bool
    {   
        return self::where([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ])->exists();
    }

}

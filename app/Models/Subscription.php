<?php

namespace App\Models;

use App\Actions\Category\GetCategoriesAction;
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
        'EXPIRED' => 'expired',
    ];

    public const LABEL = "SUB";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'currency_id', 'user_id', 'category_id', 'parent_id'
    ];

    protected $with = ['category'];

    protected $appends = ['user_uid', 'currency_code', 'parent_uid'];

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
     * Returns the service of the subscription.
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get Currency Attribute.
     *
     * @return string
     */
    public function getCurrencyCodeAttribute()
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
     * Get Parent Attribute.
     *
     * @return string
     */
    public function getParentUidAttribute()
    {
        if(!$this->parent_id) return null;

        return self::where('id', $this->parent_id)->first()->uid;
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

        if(!isset($data['category_id'])){
            $data['category_id'] = (new GetCategoriesAction)->autoCategorize($data['name']);
        }

        $subscription = self::create([
            'uid' => Str::orderedUuid(),
            'user_id' => $data['user_id'],
            'name' => ucfirst($data['name']), 
            'url' => $data['url'] ?? ($data['service_id'] ? Service::where('id', $data['service_id'])->first()->url : null),
            'currency_id' => $data['currency_id'],
            'category_id' => $data['category_id'],
            'amount' => $data['amount'],
            'status' => self::STATUS['ACTIVE'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
            'service_id' => isset($data['service_id']) ? $data['service_id'] : null
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

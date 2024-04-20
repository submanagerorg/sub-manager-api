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
        'id', 'currency_id', 'service_id', 'user_id'
    ];

    protected $with = ['service'];

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
        return $this->belongsTo(User::class, 'currency_id');
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
        if(isset($data['service_uid'])){
            $service = Service::where('uid', $data['service_uid'])->first();

        }else{
            $category = Category::where('name', 'others')->first();

            $service = Service::where('name', $data['name'])->first();

            if (!$service) {
                $service = new Service();
                $service->uid = Str::orderedUuid();
                $service->name = $data['name'];
                $service->url =  $data['url'];
                $service->category_id = $category->id;
                $service->status = Service::STATUS['PENDING'];
                $service->save();
            }

            $data['service_uid'] = $service->uid;
        }

        if(self::exists($data)){
            return null;
        }

        $subscription = self::create([
            'uid' => Str::orderedUuid(),
            'user_id' => $data['user_id'],
            'service_id' => $service->id,
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'status' => self::STATUS['ACTIVE'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);

        return $subscription->load('service');
    }

     /**
     * @param array $data
     * @return bool
     */
    public static function exists(array $data): bool
    {
        if(isset($data['service_uid'])){
            $service = Service::where('uid', $data['service_uid'])->first();
        }
        
        return self::where([
            'user_id' => $data['user_id'],
            'service_id' => isset($data['service_uid']) ? $service->id : null,
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ])->exists();
    }

}

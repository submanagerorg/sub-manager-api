<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const STATUS = [
        'ONGOING' => 'ongoing',
        'EXPIRED' => 'expired',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'currency_id'
    ];

    protected $appends = ['currency'];

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
     * Transform user id to user uid.
     *
     * @param $value
     * @return string
     */
    public function getUserIdAttribute($value): string
    {
        return User::where('id', $value)->first()->uid;
    }

    /**
     * Get Currency Attribute.
     *
     * @param $value
     * @return string
     */
    public function getCurrencyAttribute()
    {
       return Currency::where('id', $this->currency_id)->first()->symbol;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function createNew(array $data): self
    {
        return self::create([
            'uid' => Str::orderedUuid(),
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'url' => $data['url'] ?? null,
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'status' => self::STATUS['ONGOING'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);
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
            'url' => $data['url'] ?? null,
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ])->exists();
    }

}

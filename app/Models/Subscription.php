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
        'id'
    ];

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
     * Tranform user id to user uid.
     *
     * @param $value
     * @return string
     */
    public function getUserIdAttribute($value): string
    {
        return User::where('id', $value)->first()->uid;
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
            'url' => $data['url'],
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
            'url' => $data['url'],
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ])->exists();
    }

}

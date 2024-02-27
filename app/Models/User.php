<?php

namespace App\Models;

use App\Notifications\VerifyEmailQueued;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    const TOKEN_NAME = 'auth_token';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'email',
        'password',
        'username',
        'timezone_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'timezone_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['timezone'];

    /** 
     * Override sendEmailVerificationNotification implementation
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
    }

    /**
     * returns user timezone
     *
     * @return HasOne
     */
    public function timezone(): HasOne
    {
        return $this->hasOne(Timezone::class, 'id');
    }


     /**
     * returns user subscriptions
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    /**
     * Get Timezone Attribute.
     *
     * @param $value
     * @return string
     */
    public function getTimezoneAttribute()
    {
       return Timezone::where('id', $this->timezone_id)->first();
    }


     /**
     * @param array $data
     * @return self | null
     * 
     */
    public static function createNew(array $data): self | null
    {
        $timezone = Timezone::where('zone_name', Timezone::DEFAULT_TIMEZONE)->first();

        if(!$timezone){
            return null;
        }

        $name = explode("@", $data['email'])[0];
        $username = $name . random_int(1000, 9999);

        while(self::where('username', $username)->exists()){
            $username = $name . random_int(1000, 9999);
        }

        $user = self::create([
            'uid' => Str::orderedUuid(),
            'email' => $data['email'],
            'password' =>  bcrypt($data['password']),
            'username' => $username,
            'timezone_id' => $timezone->id,
        ]);

        return $user;

    }

    /**
     * @param string $email
     * @return bool
     */
    public static function exists(string $email): bool
    {
        return self::where('email', $email)->exists();
    }

}

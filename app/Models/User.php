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
        'username'
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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** 
     * Override sendEmailVerificationNotification implementation
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
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
     * @param array $data
     * @return self
     */
    public static function createNew(array $data): self
    {
        $username = explode("@", $data['email'])[0];

        return self::create([
            'uid' => Str::orderedUuid(),
            'email' => $data['email'],
            'password' =>  bcrypt($data['password']),
            'username' => $username
        ]);
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

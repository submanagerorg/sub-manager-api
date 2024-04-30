<?php

namespace App\Models;

use App\Notifications\VerifyEmailQueued;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    const TOKEN_NAME = 'auth_token';

    protected $guarded = ['id'];

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

    protected $with = ['timezone', 'pricingPlan'];

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
     * @return BelongsTo
     */
    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'timezone_id');
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
     * returns user pricing plan
     *
     * @return HasOne
     */
    public function userPricingPlan(): HasOne
    {
        return $this->hasOne(UserPricingPlan::class, 'user_id');
    }

     /**
     * returns pricing plan
     *
     * @return HasOneThrough
     */
    public function pricingPlan(): HasOneThrough
    {
        return $this->hasOneThrough(PricingPlan::class, UserPricingPlan::class, 'user_id', 'id', 'id', 'pricing_plan_id');
    }

     /**
     * @param array $data
     * @return self|null
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

       return $user->load('timezone', 'pricingPlan');
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function exists(string $email): bool
    {
        return self::where('email', $email)->exists();
    }

    /**
     * Adds pricing plan for a user and related data
     * 
     * @param string $pricingPlan
     * @param string $period
     * 
     * @return self|null
     */
    public function addUserPricingPlan($pricingPlan, $period): self | null
    {
        $userPricingPlan = UserPricingPlan::where('id', $this->id)->first();
        $pricingPlan = PricingPlan::where('name', $pricingPlan)->where('period', $period)->first();

        if (!$pricingPlan) {
            return null;
        }

        $data = [
            'pricing_plan' => $pricingPlan,
            'user_id' => $this->id
        ];

        //Add user pricing plan
        if ($userPricingPlan) {

            $userPricingPlan = $userPricingPlan::updateRecord($data);

        } else {

            $userPricingPlan = UserPricingPlan::createNew($data);
        }

        $data['userPricingPlan'] = $userPricingPlan;

        //Add user pricing plan history
        UserPricingPlanHistory::createNew($data);

        if ($userPricingPlan->end_date) {

            $service = Service::where('name', 'SubSync')->first();
            $currency = Currency::where('code', "NGN")->first();

            $data = [
                'service_uid' => $service->uid,
                'user_id' => $this->id,
                'currency_id' => $currency->id,
                'amount' => $userPricingPlan->amount,
                'start_date' => now(),
                'end_date' => $userPricingPlan->end_date,
                'description' => "SubSync {$pricingPlan->name} Subscription",
            ];

            //Add subscription
            Subscription::createNew($data);
        }
       
        return $this;
    }


    /**
     * Checks if subscription limit for a user based on their pricing plan is exceeded
     * 
     * @return bool
     */
    public function isSubscriptionLimitReached(): bool
    {
        if ($this->pricingPlan) {
            
            $subscriptionCount = $this->subscriptions()->where('status', 'active')->count();

            if ($subscriptionCount >= $this->pricingPlan->subscription_limit){
                return true;
            }
        }

        return false;
    }

    /**
     *  Checks if user can access a feature based on their pricing plan
     * 
     * @return bool
     */
    public function canAccessFeature($featureName): bool
    {
        $planFeatures = $this->pricingPlan->pricingPlanFeatures->pluck('feature.name')->toArray();
        
        return in_array(strtolower($featureName), $planFeatures);
    }

}

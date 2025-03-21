<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const DEFAULT_CURRENCY = [
        'CODE' => 'NGN',
        'SIGN' =>  '₦'
    ];

    public const STATUS = [
        'SUCCESS' => 'success',
        'FAILED' => 'failed',
        'PENDING' => 'pending'
    ];

    /**
    * Retrieve payment provider
    *
    * @return string
    */
    public static function getPaymentProvider()
    {
        $paymentProvider = "App\PaymentProviders\\" . config('providers.payment_provider');

        return $paymentProvider;
    }

    /**
    * Create new record
    *
    * @return self|null
    */
    public static function createNew(array $data): self | null
    {
        $transaction = self::create([
            'user_id' => $data['user_id'],
            'pricing_plan_id' => $data['pricing_plan_id'],
            'amount' =>  $data['amount'],
            'reference' => $data['reference'],
            'status' => $data['status'],
            'narration' => $data['narration']
        ]);

       return $transaction;
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public static function createSubscription(){
        static::create([
            'name' => request()->name,
            'description' => request()->description,
            'expiry_date' => request()->expiry_date,
            'reminder_frequency' => request()->reminder_frequency 
        ]);
    }
}

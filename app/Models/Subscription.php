<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public static function createSubscription(){
        return static::create([
            'name' => request()->name,
            'description' => request()->description,
            'expiry_date' => request()->expiry_date,
            'reminder_frequency' => request()->reminder_frequency,
            'user_id' => auth()->user()->id
        ]);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public static function generateReference()
    {
        return Str::upper(sprintf(
            '%s-%s-%s-%s',
            'TRF',
            'SBSY',            
            now()->format('YmdHis'),
            Str::random(6)
        ));
    }
    
}

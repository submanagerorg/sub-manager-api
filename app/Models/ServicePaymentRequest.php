<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePaymentRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'request_data' => 'array',
    ];

    public const STATUS = [
        'PENDING' => 'pending',
        'SUCCESSFUL' => 'successful',
        'FAILED' => 'failed'
    ];
}



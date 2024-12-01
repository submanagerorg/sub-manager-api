<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookRequestLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_request_log';

    protected $guarded = ['id'];
}

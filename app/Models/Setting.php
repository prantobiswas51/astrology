<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $fillable = [
        'stripe_api_key',
        'endpoint_secret',
        'maileroo_api_key',
    ];
}

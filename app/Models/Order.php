<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'email',
        'total_amount',
        'status',
        'notes',
        'stripe_session_id',
    ];
}

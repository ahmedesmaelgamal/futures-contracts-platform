<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'paid',
        'is_graced',
        'grace_date',
        'date',
        'grace_period',
        'status',
    ];
}

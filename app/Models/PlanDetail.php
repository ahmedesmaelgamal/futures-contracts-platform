<?php

namespace App\Models;

class PlanDetail extends BaseModel
{
    protected $fillable = [
        'plan_id',
        'key',
        'value',
        'is_unlimited',
    ];
    protected $casts = [];
}

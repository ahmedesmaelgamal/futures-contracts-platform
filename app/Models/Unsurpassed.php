<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unsurpassed extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'national_id',
        'phone',
        'office_name',
        'office_phone',
    ];
    protected $casts = [];

}

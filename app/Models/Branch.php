<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name', 'region_id', 'status','is_main'];
    protected $casts = [];


    public function region()
    {
        return $this->belongsTo(Region::class);
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

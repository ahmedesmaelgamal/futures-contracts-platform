<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;
    protected $fillable = [];
    protected $casts = [];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

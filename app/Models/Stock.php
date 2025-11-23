<?php

namespace App\Models;

class Stock extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];


    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);

    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);

    }

}

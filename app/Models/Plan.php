<?php

namespace App\Models;

class Plan extends BaseModel
{
    protected $fillable = ['name','price','period','description','image','discount','status'];
    protected $casts = [];


    public function details()
    {
        return $this->hasMany(PlanDetail::class);

    }

}

<?php

namespace App\Models;

class PlanSubscription extends BaseModel
{
    protected $fillable = ['id', 'vendor_id', 'plan_id', 'from', 'to', 'status'];
    protected $casts = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function getVendorNameAttribute()
    {
        return $this->vendor ? $this->vendor->name : '';
    }
}

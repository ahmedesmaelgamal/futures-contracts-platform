<?php

namespace App\Models;

class VendorWallet extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];


    public function vendor()
    {
        return $this->belongsTo( Vendor::class);
    }

    public function whoDoOperation()
    {
        return $this->belongsTo(Vendor::class, foreignKey: 'auth_id');
    }

}

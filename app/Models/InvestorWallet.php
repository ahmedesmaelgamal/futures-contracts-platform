<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorWallet extends BaseModel
{
    use HasFactory;


    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

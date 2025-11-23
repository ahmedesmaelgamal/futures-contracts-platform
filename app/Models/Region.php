<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends BaseModel
{
    use HasFactory;

    public function area()
    {

        return $this->belongsTo(Area::class);
    }
}

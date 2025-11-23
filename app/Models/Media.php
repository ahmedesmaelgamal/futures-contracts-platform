<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;




    protected $fillable = [
        'image',
        'modelable_type',
        'modelable_id',
    ];


    public function modelable()
    {
        return $this->morphTo();

    }

}

<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;


class City extends BaseModel
{

use HasFactory;

    protected $fillable = ['name', 'status', 'country_id'];
    protected $casts = [];




    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

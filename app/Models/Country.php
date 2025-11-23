<?php

namespace App\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;


class Country extends BaseModel
{
use HasFactory;
    protected $fillable = ['name','status'];
    protected $casts = [];





}

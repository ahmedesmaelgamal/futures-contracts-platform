<?php

namespace App\Models;

class Operation extends BaseModel
{
    protected $fillable = [];
    protected $casts = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class);

    }



}

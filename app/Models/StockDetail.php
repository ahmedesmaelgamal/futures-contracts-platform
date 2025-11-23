<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetail extends BaseModel
{
    use HasFactory;

    protected $table = 'stock_details';

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

}

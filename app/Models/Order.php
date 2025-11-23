<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;
    protected $fillable = [];
    protected $casts = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function order_status()
    {
        return $this->hasOne(OrderStatus::class);
    }



}

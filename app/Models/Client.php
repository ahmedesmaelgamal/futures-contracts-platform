<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [];
    protected $casts = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function vendor()
    {

        return Vendor::where('branch_id', $this->branch_id)->first();
    }
    public function office()
    {
        return Vendor::where('id',$this->vendor()?$this->vendor()->id:$this->vendor()->parent_id)->first();
    }

    public function orders()
    {

        return $this->hasMany(Order::class, 'client_id');
    }
}

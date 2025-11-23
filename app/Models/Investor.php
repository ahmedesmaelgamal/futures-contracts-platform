<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investor extends BaseModel
{
    use HasFactory;

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
        $office = Vendor::where('id', $this->vendor()->parent_id)->first();
        while
        ($office->parent_id) {
           $office = Vendor::where('id', $this->vendor()->parent_id)->first();
        }
    //    return $this->vendor()?($this->vendor()->parent_id?Vendor::where('id',$this->vendor()->parent_id)->first():$this->vendor()->id):null;
        return $office;
    }

}

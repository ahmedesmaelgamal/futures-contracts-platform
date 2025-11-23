<?php

namespace App\Models;

use App\Traits\AutoFillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use AutoFillable;




    protected static function booted()
    {
        static::addGlobalScope('status', function ($builder) {
            if (request('apply_scope') && Schema::hasColumn($builder->getModel()->getTable(), 'status')) {
                $builder->where('status', 1);
            }
        });
    }

    public function apply()
    {
        request()->merge(['apply_scope' => true]);
        return $this;
    }

}

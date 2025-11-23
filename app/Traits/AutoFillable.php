<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait AutoFillable
{
    public static function bootAutoFillable()
    {
        static::retrieved(function ($model) {
            if (empty($model->fillable)) {
                $model->fillable = Schema::getColumnListing($model->getTable());
            }
        });
    }
}

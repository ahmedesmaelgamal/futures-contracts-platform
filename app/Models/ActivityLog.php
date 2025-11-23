<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['userable_id', 'userable_type', 'action', 'ip_address'];

    public function userable(): MorphTo
    {
        return $this->morphTo();
    }
}

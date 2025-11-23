<?php

namespace App\Models;

use App\Traits\AutoFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
//use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;


class Vendor extends Authenticatable implements JWTSubject
{

    use AutoFillable;
    use HasApiTokens, HasFactory, Notifiable,HasRoles;



    protected $fillable = [];
    protected $casts = [];

    public function branches()
    {
        return $this->hasMany(VendorBranch::class);

    }


    public function parent()
    {
        return $this->belongsTo(Vendor::class, 'parent_id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }


    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }

}

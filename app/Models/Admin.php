<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;


    protected $fillable = [
        'name',
        'user_name',
        'code',
        'email',
        'password',
    ];


    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }



}//end class

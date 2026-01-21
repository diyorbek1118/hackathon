<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
    ];

    protected $hidden = ['password'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}

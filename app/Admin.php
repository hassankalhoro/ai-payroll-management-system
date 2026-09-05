<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
	protected $guard = 'admin';

    protected $fillable = [
        'email',
        'username',
        'image',
        'password',
        'company_name',
        'company_address',
        'company_phone',
        'company_website',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

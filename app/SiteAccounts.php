<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SiteAccounts extends Model
{

    protected $fillable = [
        'account_number',
        'account_title',
        'bank_name',
        'address',
        'is_main_account',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];




}

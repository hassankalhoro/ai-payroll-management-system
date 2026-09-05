<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Settings extends Model
{

    protected $fillable = [
        'payroll_cycle',
        'user_id',
        'notification_email',
        'number_of_hours'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    public function account(){
        return $this->hasOne(Accounts::class,'id','user_id');
    }


}

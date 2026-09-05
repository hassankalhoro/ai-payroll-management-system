<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AccountDetailTypes extends Model
{

    protected $fillable = [
        'title'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }


}

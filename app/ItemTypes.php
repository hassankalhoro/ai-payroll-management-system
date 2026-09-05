<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ItemTypes extends Model
{

    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'created_at',
    ];



    public function setTitleAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }
}

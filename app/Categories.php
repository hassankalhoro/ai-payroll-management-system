<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Categories extends Model
{

    protected $fillable = [
        'title',
        'parent_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }


}

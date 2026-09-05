<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class States extends Model
{

    protected $fillable = [
        'title',
        'shortcode',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }
    public function setShortCodeAttribute($value){
        $this->attributes['shortcode'] = strtoupper($value);
    }

}

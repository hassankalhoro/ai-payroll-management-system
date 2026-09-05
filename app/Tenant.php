<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'title',
        'email',
        'address',
        'tax',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

   /* public function getRouteKeyName()
    {
        return 'customer_id';
    }*/

    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Accounts extends Model
{

    protected $fillable = [
        'account_type',
        'title',
        'detail_type',
        'parent_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function setTitleAttribute($value){
        $this->attributes['title'] = ucwords($value);
    }
    public function accouttype(){
        return $this->hasOne(AccountTypes::class,'id','account_type');
    }
    public function detailtype(){
        return $this->hasOne(AccountDetailTypes::class,'id','detail_type');
    }
    public function parent(){
        return $this->hasOne(Accounts::class,'id','parent_id');
    }


}

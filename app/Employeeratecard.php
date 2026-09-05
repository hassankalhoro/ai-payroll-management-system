<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeeratecard extends Model
{

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'charges',
        'hours',
        'total',
    ];



    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }
}

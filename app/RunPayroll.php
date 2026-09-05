<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RunPayroll extends Model
{
    protected $fillable = [
        'employee_id',
        'unique_id',
        'start_date',
        'end_date',
        'net_pay',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }
}

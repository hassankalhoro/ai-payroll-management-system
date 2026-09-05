<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratecardextracharges extends Model
{

    protected $fillable = [
        'description',
        'year',
        'month',
        'amount',
    ];



}

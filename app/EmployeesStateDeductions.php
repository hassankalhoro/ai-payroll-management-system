<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class EmployeesStateDeductions extends Model
{
    //use HasSlug;
    protected $guarded = [];
    protected $fillable = [

    ];

    protected $casts = [
        //'amount' => 'float',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}

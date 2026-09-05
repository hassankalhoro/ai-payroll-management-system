<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class EmployeesAccounts extends Model
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

    public function getSlugOptions() : SlugOptions
    {
        /*return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();*/
    }

    public function setNameAttribute($value){
        //$this->attributes['name'] = ucwords($value);
    }

    public function getRouteKeyName()
    {
        //return 'slug';
    }
}

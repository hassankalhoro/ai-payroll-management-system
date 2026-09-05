<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'item_type',
        'sku',
        'category_id',
        'income_account_id',
        'is_sell',
        'description',
        'price_rate',
        'income_account_id',
        'sales_tax',
        'logo'
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

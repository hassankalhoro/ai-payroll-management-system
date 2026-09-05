<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banktransaction extends Model
{

    protected $fillable = [
        'transaction_date',
        'description',
        'account_id',
        'category',
        'amount',
        'remaining_balance',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'transaction_date',
    ];


    public function getDateAttribute(){
        return date("M d, Y",strtotime($this->attributes['transaction_date']));
    }

    public function setDateAttribute($value){
        $this->attributes['transaction_date'] = date("Y-m-d",strtotime($value));
    }
    public function account(){
        return $this->hasOne(SiteAccounts::class,'id','account_id');
    }
    public function invoice(){
        return $this->hasOne(Invoice::class,'id','invoice_id');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecievableDetail extends Model
{
    protected $fillable = [
        'ps_id',
        'sales_invoice_id',
        'due_date',
        'qty',
        'rate',
        'amount',
        'comments',
        'customer_id',
        'flag'
    ];

    public function PaymentSummary(){
        return $this->belongsTo(PaymentSummary::class);
    }
    public function customer(){
        return $this->hasOne(Tenant::class,'id','customer_id');
    }
}

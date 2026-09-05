<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'description',
        'qty',
        'rate',
        'amount',
        'service_id',
        'tax',
        'tax_applied'
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSummary extends Model
{

    protected $fillable = [
        'as_of_date',
        'total_receivables',
        'total_payables',
        'parent_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


}

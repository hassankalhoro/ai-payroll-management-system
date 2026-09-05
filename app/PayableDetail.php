<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayableDetail extends Model
{
    protected $fillable = [
        'ps_id',
        'expense_type',
        'expense_id',
        'expense_due_date',
        'expense_name',
        'qtyexp',
        'rateexp',
        'expense_amount',
        'expense_amount_due',
        'commentspayable',
        'expense_name_type',
        'select_employee_name',
        'paid'
    ];

    public function PaymentSummary(){
        return $this->belongsTo(PaymentSummary::class);
    }
}

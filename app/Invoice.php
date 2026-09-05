<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Invoice extends Model implements HasMedia
{
    use HasMediaTrait;
    protected $guarded = [];
    protected $fillable = [
        'customer_id',
        'invoice_date',
        'invoice_due_date',
        'note_to_employee',
        'invoice_id',
        'paidcheck',
        'paid_amount',
        'remaining_total',
        'total_iamount',
        'total_famount',
        'discount_description',
        'discount_percent',
        'discount_amount',
        'type',
        'account_id',
        'from_account',
        'notes_exp',
        'total_tax',
        'qr_code_check',
        'confirmation_number',
        'transaction_id',
        'is_merged'

        /*  'first_name',
          'last_name',
          'phone',
          'email',
          'birthdate',
          'media_id',
          'address',
          'gender',
          'remark',
          'position_id',
          'schedule_id',
          'rate_per_hour',
          'salary',
          'is_active',*/
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'invoice_date',
        'invoice_due_date',
    ];

    public function getRouteKeyName()
    {
        return 'invoice_id';
    }

    public function employee(){
        return $this->hasOne(Employee::class,'id','customer_id');
    }
    public function tenant(){
        return $this->hasOne(Tenant::class,'id','customer_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function($invoice){
            if($invoice->type==2)
            {
                $invoice->invoice_id = strtoupper(uniqid("EXP"));
            }
            else
            {
                $invoice->invoice_id = strtoupper(uniqid("INV"));
            }

        });
    }

}

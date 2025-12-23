<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    protected $primaryKey = 'invoice_payment_id';
    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'payment_date',
        'total_amount'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->invoice_payment_id;
    }
    public function invoice()
    {
        return $this->belongsTo(Tourists::class, 'tourist_id');
    }
}

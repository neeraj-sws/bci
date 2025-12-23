<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoicePayments extends Model
{
    protected $primaryKey = 'proforma_invoice_payment_id';

    protected $fillable = ['proforma_invoice_payment_id', 'proforma_invoice_id',   'quotation_id', 'payment_date', 'paid_amount', 'payment_method', 'reference', 'notes'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->proforma_invoice_payment_id;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
    }
    public function proformaInvoice()
    {
        return $this->belongsTo(ProformaInvoices::class, 'proforma_invoice_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSettings extends Model
{
    protected $primaryKey = 'invoice_setting_id';
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->invoice_setting_id;
    }
    protected $fillable = ['invoice_number', 'invoice_title','pr_invoice_title', 'payment_terms', 'column_layout', 'terms_condition',    'customer_note','company_id'];
}

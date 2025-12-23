<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeExpenses extends Model
{
    protected $primaryKey = 'income_expense_id';

    protected $fillable = [
        'date',
        'category_id',
        'sub_category_id',
        'vendor_name',
        'amount',
        'reference',
        'tourist_id',
        'tour_id',
        'notes',
        'quotation_id',
        'type',
        'entry_type','soft_delete','proforma_invoice_id','vendor_id','payment_reference'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->income_expense_id;
    }

    public function category()
    {
        return $this->belongsTo(IncomeExpenseCategory::class, 'category_id');
    }
    public function client()
    {
        return $this->belongsTo(Tourists::class, 'client_id');
    }
    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tour_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendors::class, 'vendor_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
    }
        public function proforma()
    {
        return $this->belongsTo(ProformaInvoices::class, 'proforma_invoice_id');
    }
}

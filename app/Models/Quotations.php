<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotations extends Model
{
    protected $primaryKey = 'quotation_id';

    protected $fillable = [
        'uuid',
        'quotation_no',
        'quotation_title',
        'po_number',
        'quotation_date',
        'start_date',
        'end_date',
        'tour_id',
        'expiry_date',
        'amount',
        'notes',
        'terms_and_condition',
        'status',
        'user_id',
        'lead_id',
        'tourist_id',

        // NEW DEV 
        'revision_no',
        'version_of',
        'total_revised',
        'revised_no',
        'company_id',
        'sub_amount',
        'discount_amount',

        'inr_amount',
        'usd_amount',
        'currency_label',
        'attachment',
        'is_attachment',

        'total_paid_amount',
        'total_remaning_amount'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->quotation_id;
    }


    public function tourist()
    {
        return $this->belongsTo(Tourists::class, 'tourist_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tour_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItems::class, 'quotation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function leads()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }

    // NEW DEV 
    public function version_of()
    {
        return $this->belongsTo(Quotations::class, 'version_of');
    }

    public function invoice()
    {
        return $this->hasOne(Invoices::class, 'quotation_id');
    }

    // NEW DEV 
    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function expenses()
    {
        return $this->hasMany(IncomeExpenses::class, 'quotation_id');
    }

    public function lastprinvoice()
    {
        return $this->hasOne(ProformaInvoices::class, 'quotation_id')->latest('proforma_invoice_id');
    }
    public function prinvoice()
    {
        return $this->hasMany(ProformaInvoices::class, 'quotation_id');
    }
}

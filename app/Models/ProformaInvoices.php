<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoices extends Model
{
    protected $primaryKey = 'proforma_invoice_id';

    protected $fillable = [
        'uuid',
        'quotation_id',
        'proforma_invoice_no',
        'proforma_invoice_title',
        'proforma_invoice_date',
        'tour_id',
        'expiry_date',
        'amount',
        'status',
        'user_id',
        'lead_id',
        'tourist_id',
        'company_id',
        'sub_amount',
        'discount_amount',
        'inr_amount',
        'usd_amount',
        'currency_label',
        'attachment',
        'is_attachment',
        
        'total_remaning_amount',
        'total_paid_amount'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->proforma_invoice_id;
    }


    public function tourist()
    {
        return $this->belongsTo(Tourists::class, 'tourist_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tour_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
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
    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }
}

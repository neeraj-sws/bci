<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'uuid',
        'invoice_no',
        'invoice_title',
        'tour_id',
        'quotation_id',
        'invoice_date',
        'expiry_date',
        'sub_amount',
        'discount_amount',
        'amount',
        'inr_amount',
        'usd_amount',
        'currency_label',
        'status',
        'user_id',
        'lead_id',
        'tourist_id',
        'company_id',
        
        'type',
        'tax_id',
        'tax_amount'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->invoice_id;
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
    public function lead()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }
    
    public function tax_id()
    {
        return $this->belongsTo(TouristOtherDetails::class, 'tour_id')->select('tax_id');
    }
}

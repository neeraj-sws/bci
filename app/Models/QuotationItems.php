<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItems extends Model
{
    protected $primaryKey = 'quotation_item_id';

    protected $fillable = ['quotation_id', 'json',   'item_name', 'description', 'is_tour', 'amount', 'inr_amount', 'usd_amount', 'currency_label','is_custome'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->quotation_item_id;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
    }
}

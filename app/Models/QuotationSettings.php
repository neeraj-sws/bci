<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationSettings extends Model
{
    protected $primaryKey = 'quotation_setting_id';

    protected $fillable = ['estimate_number', 'estimate_title', 'terms_condition', 'customer_note','company_id'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->quotation_setting_id;
    }
}

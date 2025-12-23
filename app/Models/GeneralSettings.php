<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $primaryKey = 'general_setting_id';

    protected $fillable = ['fiscal_year', 'currency', 'usd_rate', 'markup_rate', 'date_format', 'paper_size',    'number_format', 'pdf_attachment', 'notify', 'notify2',    'notify3','company_id'];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->general_setting_id;
    }
    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year');
    }

    public function base_currency()
    {
        return $this->belongsTo(Currency::class, 'currency');
    }
}

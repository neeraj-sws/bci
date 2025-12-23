<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonColumnSettings extends Model
{
    protected $primaryKey = 'common_column_setting_id';

    protected $fillable = ['items', 'units', 'amount', 'date', 'time', 'custom', 'hide_quantity', 'hide_rate', 'hide_amount','company_id'];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->common_column_setting_id;
    }
}

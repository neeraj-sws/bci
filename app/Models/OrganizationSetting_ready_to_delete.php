<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationSetting extends Model
{
    protected $table = 'organization_setting';
    protected $primaryKey = 'organization_setting_id';


    protected $fillable = [
        'organization_name',
        'city',
        'state',
        'zip_code',
        'country',
        'street_address',
        'time_zone',
        'company_tax_id',
        'phone',
        'fax_number',
        'website',
        'fiscal_year',
        'currency',
        'language',
        'file_id',
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->organization_setting_id;
    }

    public function logo()
    {
        return $this->belongsTo(UploadImages::class, 'file_id');
    }

    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year');
    }
}

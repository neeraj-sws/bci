<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model

{

    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $table = 'companies';
    protected $primaryKey = 'company_id';


    protected $fillable = [
        'company_name',
        'company_email',
        'company_contact',
        'company_address',
        'company_file_id',
        'city',
        'state',
        'zip_code',
        'country',
        'time_zone',
        'company_tax_id',
        'fax_number',
        'website',
        'fiscal_year',
        'currency',
        'language',
        'uuid',
        'profile_steps','is_primary','sac_code'
    ];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->company_id;
    }

    public function logo()
    {
        return $this->belongsTo(UploadImages::class, 'company_file_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Taxes extends Model

{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $primaryKey = 'taxe_id';

    protected $fillable = [
        'tax_name',
        'rate','company_id'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->taxe_id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingCompany extends Model
{
    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'marketing_company_id';

    protected $fillable = ['title', 'status'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->marketing_company_id;
    }
}

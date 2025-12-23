<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    protected $primaryKey = 'fiscal_year_id';

    protected $table = 'fiscal_year';
    protected $fillable = [
        'name',
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->fiscal_year_id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model

{
    protected $table = 'currency';
    protected $primaryKey = 'currency_id';

    protected $fillable = [
        'currency',
        'code'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->currency_id;
    }
}

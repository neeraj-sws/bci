<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model

{
    protected $table = 'country';
    protected $primaryKey = 'country_id';

    protected $fillable = [
        'countrycode',
        'name',
        'code'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->country_id;
    }
}

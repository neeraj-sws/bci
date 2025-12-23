<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristOtherDetails extends Model
{
    protected $primaryKey = 'tourist_other_id';
    protected $fillable = ['tourist_id',    'tax_id', 'website', 'payment_terms'];
    // ID ALIAS 
    public function getIdAttribute()
    {
        return $this->tourist_other_id;
    }
}

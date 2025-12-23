<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAdditions extends Model
{
    protected $primaryKey = 'lead_addition_id';

    protected $fillable = ['lead_id', 'address', 'city', 'state', 'country_id', 'destination',    'travel_date', 'travel_days',    'budget', 'follow_up_date', 'follow_up_time'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_addition_id;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}

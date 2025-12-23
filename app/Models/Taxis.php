<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxis extends Model
{
    protected $table = 'taxis';

    protected $primaryKey = 'taxi_id';


    protected $fillable = [
        'city_id',
        'park_id',
        'zone_id',
        'sedan',
        'crysta',
        'sedan_retained',
        'crysta_retained',
        'sedan_retained_two',
        'crysta_retained_two'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->taxi_id;
    }

    public function park()
    {
        return $this->belongsTo(Parks::class, 'park_id');
    }
    public function zone()
    {
        return $this->belongsTo(Zones::class, 'zone_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}

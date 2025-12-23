<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Zones extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'zone_id';

    protected $fillable = [
        'park_id',
        'name',
        'nearest_airport',
        'nearest_railway',
        'nearest_city',
        'full_day_safari_cost',
        'total_cost',
        'allowed_gates',
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->zone_id;
    }


    public function park()
    {
        return $this->belongsTo(Parks::class, 'park_id');
    }
}

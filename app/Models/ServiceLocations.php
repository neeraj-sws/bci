<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLocations extends Model
{
    protected $primaryKey = 'service_location_id';

    protected $fillable = ['name', 'status','soft_delete'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->service_location_id;
    }
}

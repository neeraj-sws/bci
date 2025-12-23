<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorsVehicles extends Model
{
    protected $primaryKey = 'vendors_vehicle_id';

    protected $fillable = ['vendor_id', 'vehicle_id', 'night_charge', 'day_charge'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->vendors_vehicle_id;
    }
}

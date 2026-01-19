<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendors extends Model
{
    protected $primaryKey = 'vendor_id';

    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';

    protected $fillable = ['type_id', 'sub_type_id', 'name', 'contact', 'secondary_contact', 'city_id', 'status', 'address', 'state_id', 'country_id', 'base_location_id', 'notes', 'soft_delete'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->vendor_id;
    }
    public function vehicles()
    {
        return $this->hasMany(VendorsVehicles::class, 'vendor_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function type()
    {
        return $this->belongsTo(VendorTypes::class, 'type_id');
    }

    public function serviceLocations()
    {
        return $this->hasMany(VendorServiceLocations::class, 'vendor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VendorServiceLocations extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'vendor_service_location_id';

    protected $fillable = ['vendor_id', 'vendor_service_area_id'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->vendor_service_location_id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceLocations extends Model
{
    use SoftDeletes;
    protected $dates = ['soft_delete'];
    const DELETED_AT = 'soft_delete';

    protected $primaryKey = 'service_location_id';

    protected $fillable = ['name', 'status', 'soft_delete'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->service_location_id;
    }
}

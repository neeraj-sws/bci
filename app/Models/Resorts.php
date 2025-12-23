<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Resorts extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'resort_id';

    protected $fillable = ['name', 'primary_contact', 'phone', 'secondary_phone', 'park_id',    'zone_id',    'location_gate',    'drive_link', 'address'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->resort_id;
    }

    public function park()
    {
        return $this->belongsTo(Parks::class, 'park_id');
    }
    public function zone()
    {
        return $this->belongsTo(Zones::class, 'zone_id');
    }

    public function categories()
    {
        return $this->hasMany(ResortCategorys::class, 'resort_id');
    }
}

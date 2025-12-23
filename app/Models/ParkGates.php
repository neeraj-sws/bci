<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ParkGates extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
  protected $primaryKey = 'park_gate_id';

  protected $fillable = ['park_id', 'zone_id', 'name',    'gypsy_charge', 'guide_fee', 'gate_to_gate', 'weekday_permit', 'weekend_permit', 'total_week_day', 'total_week_end',    'night_safari_permit', 'drive_image'];

  // ID ALIAS
  public function getIdAttribute()
  {
    return $this->park_gate_id;
  }

  public function park()
  {
    return $this->belongsTo(Parks::class, 'park_id');
  }
  public function zone()
  {
    return $this->belongsTo(Zones::class, 'zone_id');
  }
}

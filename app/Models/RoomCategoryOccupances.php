<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategoryOccupances extends Model
{
    use SoftDeletes;

    protected $table = 'room_category_occupances';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'room_category_occupancy_id';

    protected $fillable = [
        'room_category_id',
        'occupancy_id',
        'rate',
        'weekend_rate',
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->room_category_occupancy_id;
    }

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id', 'room_categoris_id');
    }

    public function occupancy()
    {
        return $this->belongsTo(Occupancy::class, 'occupancy_id', 'occupancy_id');
    }
}

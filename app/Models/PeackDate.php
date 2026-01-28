<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeackDate extends Model
{
    use SoftDeletes;

    protected $table = 'peak_dates';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'peak_dates_id';

    protected $fillable = [
        "hotel_id",
        "title",
        "is_new_year",
        "status",
        "room_category_id",
        "notes"
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotels_id');
    }


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->peak_dates_id;
    }
    
    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id', 'room_categoris_id');
    }
    
    public function occupancies()
    {
        return $this->hasMany(PeakDateRoomCategoryOccupances::class, 'peak_date_id', 'peak_dates_id');
    }
    
    public function childPolicies()
    {
        return $this->hasMany(ChildPolicy::class, 'peak_date_id', 'peak_dates_id');
    }
}

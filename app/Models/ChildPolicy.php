<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildPolicy extends Model
{
    use SoftDeletes;

    protected $table = 'child_policies';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'child_policies_id';

    protected $fillable = [
        "hotel_id",
        "free_child_age",
        "child_with_bed_rate",
        "child_without_bed_rate",
        "status",
        "room_category_id",
        "peak_date_id"
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotels_id');
    }

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id', 'room_categoris_id');
    }

    public function peakDate()
    {
        return $this->belongsTo(PeackDate::class, 'peak_date_id', 'peak_dates_id');
    }

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->child_policies_id;
    }
}

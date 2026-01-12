<?php

namespace App\Models;

use App\Livewire\Common\HotelMaster\PeakDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeakDateRoomCategoryOccupances extends Model
{
    use SoftDeletes;

    protected $table = 'peak_date_room_category_occupances';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'peak_date_room_category_occupancy_id';

    protected $fillable = [
        'peak_date_id',
        'occupancy_id',
        'rate',
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->peak_date_room_category_occupancy_id;
    }
    public function peakDates()
{
    return $this->belongsTo(PeakDates::class, 'peak_dates_id', 'id');
}

}

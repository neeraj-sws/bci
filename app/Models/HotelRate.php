<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRate extends Model
{
    use SoftDeletes;

    protected $table = 'hotel_rates';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'hotel_rates_id';

    protected $fillable = [
        "hotel_id",
        "room_category_id",
        "rate_type_id",
        "season_id",
        "meal_plan_id",
        "occupancy_id",
        "weekday_rate",
        "weekend_rate",
        "child_rate",
        "extra_bed_rate",
        "status"
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotels_id');
    }
    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id', 'room_categoris_id');
    }
    public function rateType()
    {
        return $this->belongsTo(RateTypes::class, 'rate_type_id', 'rate_type_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'seasons_id');
    }
    public function mealPlan()
    {
        return $this->belongsTo(MealType::class, 'meal_plan_id', 'meal_plans_id');
    }
    public function occupancy()
    {
        return $this->belongsTo(Occupancy::class, 'occupancy_id', 'occupancy_id');
    }



    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->hotel_rates_id;
    }
}

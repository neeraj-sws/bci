<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $table = 'hotels';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'hotels_id';

    protected $fillable = ["name", "hotel_type_id", "hotel_category_id", "parent_chain_id",    "marketing_company_id", "location", "status","rate_type_id"];

    public function hotelType()
    {
        return $this->belongsTo(HotelTypes::class, 'hotel_type_id', 'hotel_type_id');
    }
     public function hotelCategory()
    {
        return $this->belongsTo(HotelCategories::class, 'hotel_category_id', 'hotel_category_id');
    }

    public function hotelRateType()
    {
        return $this->belongsTo(RateTypes::class, 'rate_type_id', 'rate_type_id');
    }
    public function hotelMealType()
    {
        return $this->hasMany(HotelMealPlan::class, 'hotel_id', 'hotels_id');
    }

    public function marketingCompany()
    {
        return $this->belongsTo(Hotel::class, 'marketing_company_id', 'hotels_id');
    }

    public function parentChain()
    {
        return $this->belongsTo(Hotel::class, 'parent_chain_id', 'hotels_id');
    }

    public function roomCategories()
    {
        return $this->hasMany(RoomCategory::class, 'hotel_id', 'hotels_id');
    }

    public function hotelRates()
    {
        return $this->hasMany(HotelRate::class, 'hotel_id', 'hotels_id');
    }

    public function peakDates()
    {
        return $this->hasMany(PeackDate::class, 'hotel_id', 'hotels_id');
    }

    public function childPolicies()
    {
        return $this->hasMany(ChildPolicy::class, 'hotel_id', 'hotels_id');
    }

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->hotels_id;
    }
}

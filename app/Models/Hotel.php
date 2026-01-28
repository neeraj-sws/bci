<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class Hotel extends Model
{
    use SoftDeletes;

    protected $table = 'hotels';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'hotels_id';

    protected $fillable = ["name", "hotel_type_id", "hotel_category_id", "parent_chain_id",    "marketing_company_id", "country_id","state_id","city_id", "status","rate_type_id","park_id"];

    protected static function boot()
    {
        parent::boot();


        static::deleting(function ($hotel) {

            foreach ($hotel->peakDates as $peakDate) {
                $peakDate->occupancies()->delete();
                $peakDate->delete();
            }
            foreach ($hotel->roomCategories as $roomCategory) {
                $roomCategory->occupancies()->delete();
                ChildPolicy::where('room_category_id', $roomCategory->room_categoris_id)->delete();
                $roomCategory->delete();
            }
            $hotel->hotelMealType()->delete();
            $hotel->hotelRates()->delete();
            $hotel->childPolicies()->delete();
        });

        static::forceDeleting(function ($hotel) {

            foreach ($hotel->peakDates()->withTrashed()->get() as $peakDate) {
                $peakDate->occupancies()->forceDelete();
                $peakDate->forceDelete();
            }

            foreach ($hotel->roomCategories()->withTrashed()->get() as $roomCategory) {
                $roomCategory->occupancies()->forceDelete();
                ChildPolicy::withTrashed()->where('room_category_id', $roomCategory->room_categoris_id)->forceDelete();
                $roomCategory->forceDelete();
            }

            $hotel->hotelMealType()->forceDelete();
            $hotel->hotelRates()->forceDelete();
            $hotel->childPolicies()->forceDelete();
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(States::class, 'state_id', 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
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
        return $this->belongsTo(MarketingCompany::class, 'marketing_company_id', 'marketing_company_id');
    }

    public function parentChain()
    {
        return $this->belongsTo(Chain::class, 'parent_chain_id', 'chain_id');
    }

    public function park()
    {
        return $this->belongsTo(Parks::class, 'park_id', 'park_id');
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

    public function supplements()
    {
        return $this->hasMany(Supplement::class, 'hotel_id', 'hotels_id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class, 'hotel_id', 'hotels_id');
    }

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->hotels_id;
    }
}

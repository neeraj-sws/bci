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

    protected $fillable = ["name", "hotel_type_id", "hotel_category_id", "parent_chain_id",    "marketing_company_id", "location", "status"];

    public function hotelType()
    {
        return $this->belongsTo(HotelTypes::class, 'hotel_type_id');
    }
     public function hotelCategory()
    {
        return $this->belongsTo(HotelCategories::class, 'hotel_category_id');
    }

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->hotels_id;
    }
}

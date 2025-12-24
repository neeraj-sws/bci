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
        "start_date",
        "end_date",
        "is_new_year",
        "status"
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
}

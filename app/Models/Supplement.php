<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplement extends Model
{
    use SoftDeletes;

    protected $table = 'supplements';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'supplements_id';

    protected $fillable = [
        "hotel_id",
        "peak_date_id",
        "title",
        "amount",
        "mandatory",
        "status"
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotels_id');
    }


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->supplements_id;
    }
}

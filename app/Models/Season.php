<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes;

    protected $table = 'seasons';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'seasons_id';

    protected $fillable = [
        'name',
        'hotel_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotels_id');
    }


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->seasons_id;
    }
}

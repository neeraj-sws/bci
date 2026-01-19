<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripItem extends Model
{
    protected $table = 'trip_items';
    protected $primaryKey = 'trip_item_id';

    protected $fillable = [
        'trip_id',
        'quotation_id',
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->trip_item_id;
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
    }
}

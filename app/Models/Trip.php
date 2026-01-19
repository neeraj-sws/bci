<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    protected $table = 'trips';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'trip_id';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->trip_id;
    }
    public function items()
    {
        return $this->hasMany(TripItem::class, 'trip_id');
    }
}

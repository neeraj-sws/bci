<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategory extends Model
{
    use SoftDeletes;

    protected $table = 'room_categoris';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'room_categoris_id';

    protected $fillable = [
        'title',
        'hotel_id',
        'base_occupancy',
        'max_occupancy',
        'status'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->room_categoris_id;
    }
    public function occupancies()
{
    return $this->hasMany(RoomCategoryOccupances::class, 'room_category_id', 'id');
}

}

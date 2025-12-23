<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occupancy extends Model
{
    use SoftDeletes;

    protected $table = 'occupances';
    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'occupancy_id';

    protected $fillable = ['title', 'status'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->occupancy_id;
    }
}

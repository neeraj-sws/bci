<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tours extends Model
{

    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';

    protected $primaryKey = 'tour_id';

    protected $fillable = [
        'name',
        'day',
        'soft_name',
        'night',
        'description',
        'status','attachment'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->tour_id;
    }
    public function tourJsons()
    {
        return $this->hasMany(TourJsons::class, 'tour_id');
    }
}

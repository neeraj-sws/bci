<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourJsons extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $primaryKey = 'tour_json_id';

    protected $fillable = ['tour_id', 'json'];
    // ID ALIAS 
    public function getIdAttribute()
    {
        return $this->tour_id;
    }
    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tour_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ResortCategorys extends Model
{
    
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'resort_category_id ';

    protected $fillable = ['resort_id', 'name', 'regular_rate', 'high_season_rate', 'extra_child_rate',    'extra_adult_rate'];
    public function getIdAttribute()
    {
        return $this->resort_category_id;
    }

    public function resort()
    {
        return $this->belongsTo(Resorts::class, 'resort_id');
    }
}

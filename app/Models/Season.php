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
        'start_date',
        'end_date',
        'status'
    ];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->seasons_id;
    }

    // SEASON_ID ALIAS for consistency
    public function getSeasonIdAttribute()
    {
        return $this->seasons_id;
    }

    // TITLE ACCESSOR for consistency
    public function getTitleAttribute()
    {
        return $this->name;
    }
}

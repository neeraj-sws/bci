<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadStages extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'lead_stage_id';

    protected $fillable = [
        'name',
        'soft_name',
        'btn_bg',
        'btn_text'
    ];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_stage_id;
    }
}

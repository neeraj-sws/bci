<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadSources extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'lead_source_id';

    protected $fillable = ['name'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_source_id;
    }
}

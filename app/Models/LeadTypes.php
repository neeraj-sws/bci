<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadTypes extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $primaryKey = 'lead_type_id';
    protected $fillable = ['name', 'color'];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_type_id;
    }
}

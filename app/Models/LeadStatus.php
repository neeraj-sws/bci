<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadStatus extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $fillable = [
        'name',
        'type_id',
        'btn_bg',
        'btn_text'
    ];

    protected $table = 'lead_status';

    protected $primaryKey = 'lead_status_id';

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_status_id;
    }
    public function type()
    {
        return $this->belongsTo(LeadTypes::class, 'type_id');
    }
}

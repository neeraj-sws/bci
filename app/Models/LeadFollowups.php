<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadFollowups  extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $primaryKey = 'lead_followup_id';

    protected $fillable = ['lead_id',    'followup_date',    'followup_time', 'stage_id',    'status_id', 'comments', 'user_id', 'marketing_person', 'mark', 'is_read'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_followup_id;
    }

    public function lead()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }


    public function stage()
    {
        return $this->belongsTo(LeadStages::class, 'stage_id');
    }
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

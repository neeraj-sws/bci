<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications  extends Model
{

    protected $primaryKey = 'notification_id';

    protected $fillable = ['msg_type',    'lead_id',    'user_id', 'is_read', 'followup_id'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->notification_id;
    }
    public function lead()
    {
        return $this->belongsTo(Leads::class, 'lead_id');
    }
    public function msg()
    {
        return $this->belongsTo(ActivityType::class, 'msg_type');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function followup()
    {
        return $this->belongsTo(LeadFollowups::class, 'followup_id');
    }
}

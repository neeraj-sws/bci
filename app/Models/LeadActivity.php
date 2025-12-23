<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivity  extends Model
{

    protected $table = 'lead_activity';
    protected $primaryKey = 'lead_activity_id';


    protected $fillable = ['lead_id',    'msg_type', 'user_id', 'marketing_person'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_activity_id;
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
}

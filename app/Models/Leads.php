<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $primaryKey = 'lead_id';

    protected $fillable = ['uuid', 'type_id', 'soft_delete', 'last_stage',   'tourist_id',    'contact', 'email',    'stage_id',    'status_id', 'source_id', 'additional_info', 'notes', 'user_id', 'marketing_person', 'follow_up_time', 'follow_up_date', 'budget', 'travel_days', 'travel_date', 'destination','tags'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->lead_id;
    }

    public function stage()
    {
        return $this->belongsTo(LeadStages::class, 'stage_id');
    }
    public function type()
    {
        return $this->belongsTo(LeadTypes::class, 'type_id');
    }
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function source()
    {
        return $this->belongsTo(LeadSources::class, 'source_id');
    }


    public function followups()
    {
        return $this->hasMany(LeadFollowups::class, 'lead_id');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class, 'lead_id');
    }

    public function images()
    {
        return $this->hasMany(UploadImages::class, 'lead_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tourist()
    {
        return $this->belongsTo(Tourists::class, 'tourist_id');
    }


    public function quotation()
    {
        return $this->hasOne(Quotations::class, 'lead_id');
    }
    public function invoice()
    {
        return $this->hasOne(Invoices::class, 'lead_id');
    }
}

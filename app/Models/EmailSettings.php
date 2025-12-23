<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    protected $primaryKey = 'email_setting_id';

    protected $fillable = ['subject', 'message', 'type','company_id'];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->email_setting_id;
    }
}

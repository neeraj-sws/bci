<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType  extends Model
{

    protected $table = 'activity_type';

    protected $primaryKey = 'activity_type_id';



    protected $fillable = ['message_type'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->activity_type_id;
    }
}

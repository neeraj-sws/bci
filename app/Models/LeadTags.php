<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LeadTags extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
   protected $primaryKey = 'lead_tag_id';

   protected $fillable = ['name', 'soft_name', 'status'];

   // ID ALIAS
   public function getIdAttribute()
   {
      return $this->lead_tag_id;
   }
}

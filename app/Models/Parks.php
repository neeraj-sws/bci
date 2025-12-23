<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Parks extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
   protected $primaryKey = 'park_id';

   protected $fillable = ['name', 'status'];

   // ID ALIAS
   public function getIdAttribute()
   {
      return $this->park_id;
   }
}

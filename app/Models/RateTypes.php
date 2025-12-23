<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RateTypes extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
   protected $primaryKey = 'rate_type_id';

   protected $fillable = ['title', 'status'];

   // ID ALIAS
   public function getIdAttribute()
   {
      return $this->rate_type_id;
   }
}

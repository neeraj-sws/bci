<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorTypes extends Model
{
   protected $primaryKey = 'vendor_type_id';

   protected $fillable = ['name', 'status','soft_delete'];

   // ID ALIAS
   public function getIdAttribute()
   {
      return $this->vendor_type_id;
   }
}

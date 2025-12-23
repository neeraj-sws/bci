<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model

{

    protected $table = 'city';
    protected $primaryKey = 'city_id';


    protected $fillable = [
        'name',
        'status'
    ];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->city_id;
    }
}

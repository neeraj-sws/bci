<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model

{
    protected $primaryKey = 'state_id';

    protected $fillable = [
        'name'
    ];


    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->state_id;
    }
}

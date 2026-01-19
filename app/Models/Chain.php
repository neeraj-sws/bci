<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chain extends Model
{
    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'chain_id';

    protected $fillable = ['title', 'status'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->chain_id;
    }
}

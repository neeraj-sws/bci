<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Items extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'name',
        'sku',
        'rate',
        'unit',
        'tax_id',
        'type',
        'status',
        'description'
    ];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->item_id;
    }

    public function getFullNameAttribute()
    {
        return $this->sku ? $this->name . ' (' . $this->sku . ')' : $this->name;
    }
    public function taxe()
    {
        return $this->belongsTo(Taxes::class, 'tax_id');
    }
}

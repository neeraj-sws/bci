<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealType extends Model
{
    use SoftDeletes;

    protected $dates = ['soft_delete'];

    protected $table = 'meal_plans';
    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'meal_plans_id';

    protected $fillable = ['title', 'short_description','status'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->meal_plans_id;
    }
}

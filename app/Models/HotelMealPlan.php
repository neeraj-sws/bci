<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelMealPlan extends Model
{
    protected $table = 'hotel_meal_plans';

    protected $primaryKey = 'hotel_meal_plan_id';

    protected $fillable = ['hotel_id',"meal_plan_id"];

     public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_plan_id', 'meal_plans_id');
    }

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->hotel_meal_plan_id;
    }

}

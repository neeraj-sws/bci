<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    protected $primaryKey = 'vendor_id';

    protected $fillable = ['type_id','sub_type_id', 'name', 'contact', 'secondary_contact', 'city_id', 'status', 'address', 'state_id', 'country_id', 'base_location_id', 'notes','soft_delete'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->vendor_id;
    }
    public function vehicles()
    {
        return $this->hasMany(VendorsVehicles::class, 'vendor_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function type()
    {
        return $this->belongsTo(VendorTypes::class, 'type_id');
    }
    
    public function serviceLocations()
    {
        return $this->hasMany(VendorServiceLocations::class, 'vendor_id');
    }

    public function expenseType()
    {
        return $this->belongsTo( IncomeExpenseCategory::class, 'type_id',  'income_expense_category_id');
    }

    public function expenseSubType()
    {
        return $this->belongsTo( IncomeExpenseSubCategory::class, 'sub_type_id', 'income_expense_sub_category_id');
    }
}

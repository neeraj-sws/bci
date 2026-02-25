<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tourists extends Model
{
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'tourist_id';
    protected $fillable = ['flag', 'company_name', 'date', 'source_id', 'primary_contact',    'contact_email', 'contact_phone', 'address', 'city_suburb', 'state',    'zip_code', 'country_id', 'birthday', 'anniversary', 'other_id', 'base_currency_code', 'reference'];

    // ID ALIAS 
    public function getIdAttribute()
    {
        return $this->tourist_id;
    }

    public function other()
    {
        return $this->belongsTo(TouristOtherDetails::class, 'other_id');
    }
    
        public function tax_id()
    {
        return $this->belongsTo(TouristOtherDetails::class, 'other_id')->select('tax_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_suburb');
    }
    public function stateRelation()
    {
        return $this->belongsTo(States::class, 'state');
    }

    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'tourist_id');
    }
    public function quotation()
    {
        return $this->hasMany(Quotations::class, 'tourist_id');
    }
 public function expenses()
{
    return $this->hasMany(IncomeExpenses::class, 'tourist_id', 'tourist_id');
}

}

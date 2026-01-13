<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class IncomeExpenseCategory extends Model

{
    
        use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    
    protected $table = 'income_expense_category';
    protected $primaryKey = 'income_expense_category_id';

    protected $fillable = [
        'name',
        'soft_name',
        'type',
        'status',
        'is_taxi',
        'is_tour'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->income_expense_category_id;
    }
}

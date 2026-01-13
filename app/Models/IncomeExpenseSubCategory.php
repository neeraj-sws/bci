<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeExpenseSubCategory extends Model

{

    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';

    protected $table = 'income_expense_sub_category';
    protected $primaryKey = 'income_expense_sub_category_id';

    protected $fillable = [
        'name',
        'soft_name',
        'type',
        'status',
        'category_id'
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->income_expense_sub_category_id;
    }

        public function category()
    {
        return $this->belongsTo(IncomeExpenseCategory::class, 'category_id');
    }
}

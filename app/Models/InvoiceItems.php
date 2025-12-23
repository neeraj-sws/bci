<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    protected $primaryKey = 'invoice_item_id';

    protected $fillable = ['invoice_id', 'json',   'item_name', 'description', 'is_tour', 'amount', 'is_custome'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->invoice_item_id;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }
}

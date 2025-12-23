<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvEstActivity  extends Model
{

    protected $table = 'inv_est_activity';

    protected $primaryKey = 'inv_est_activity_id';


    protected $fillable = ['invoice_id', 'quotation_id', 'proforma_invoice_id', 'msg_type', 'user_id'];

    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->inv_est_activity_id;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }
    public function proformainvoice()
    {
        return $this->belongsTo(ProformaInvoices::class, 'proforma_invoice_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotations::class, 'quotation_id');
    }

    public function msg()
    {
        return $this->belongsTo(ActivityType::class, 'msg_type');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

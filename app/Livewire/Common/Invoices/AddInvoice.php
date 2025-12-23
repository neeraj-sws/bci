<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\Leads;
use App\Models\Quotations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, On, Rule};
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('components.layouts.common-app')]
class AddInvoice extends Component
{
    public $pageTitle = "Invoice", $showModal = false;

    public $route;
    public $quotation_id, $quotation, $prinvoiceSettings;
    public $invoice_date, $expiry_date;
    public $invoice_no;
    
    // NEW TAX RELATED 
    public $is_tax = 0, $tax_id, $showTaxModel = false;
    public $grand_total, $sub_total, $tax_amount;


    public function loadQuotation($quotation_id = null)
    {
        $this->quotation = Quotations::with([
            'items',
            'tourist.tax_id'
        ])->where('uuid', $quotation_id)->firstOrFail();
        
        if ($this->quotation) {
            $this->quotation_id = $this->quotation->id;
            $this->invoice_no = SettingHelper::getInvoiceNumber($this->quotation->company_id);
            
            // NEW DEV TAX RELATED 
            $this->grand_total = $this->quotation->amount;
            if ($this->quotation->tourist->tax_id) {
                $this->tax_id = $this->quotation->tourist->tax_id;
            }
            
        }
    }

    public function mount($quotation_id = null)
    {
        $this->route = 'common';

        if ($quotation_id) {
            $this->loadQuotation($quotation_id);
        }
        $this->expiry_date = Carbon::now()->addDays(4)->format('Y-m-d');
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        $this->prinvoiceSettings = InvoiceSettings::where('company_id', $this->quotation->company_id)->first();
    }
    public function render()
    {
        return view('livewire.common.invoices.invoice-add');
    }


    public function addInvoice()
    {
        $invoice = Invoices::create([
            'invoice_no' => $this->invoice_no,
            'invoice_title' => $this->quotation->quotation_title,
            'invoice_date' => $this->invoice_date,
            'tour_id' =>  $this->quotation->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->quotation->amount,
            'lead_id' => $this->quotation->lead_id,
            'tourist_id' => $this->quotation->tourist_id,
            'user_id' => Auth::guard('web')->user()->id,
            'company_id' => $this->quotation->company_id,
            'quotation_id' => $this->quotation->quotation_id,
            'currency_label' => $this->quotation->currency_label,
            'discount_amount' => $this->quotation->discount_amount,
            'sub_amount' => $this->quotation->sub_amount,

            'inr_amount' => $this->quotation->inr_amount,
            'usd_amount' => $this->quotation->usd_amount,
            
            // NEW DEV  TAX RELATED 
            'type' => $this->is_tax ? 2 : 1,
            'tax_amount' => $this->tax_amount,
            'tax_id' => $this->tax_id
        ]);
        $leadUuid = Str::uuid()->toString() . '-' . $invoice->id . '-' . Str::uuid()->toString();
        $encodedUuid = base64_encode($leadUuid);
        $invoice->uuid = $encodedUuid;
        $invoice->save();

        if ($this->quotation->lead_id) {
            $lead = Leads::findOrFail($this->quotation->lead_id);
            $lead->update([
                "stage_id" => 7
            ]);
        }
        if ($this->quotation->quotation_id) {
            Quotations::where('quotation_id', $this->quotation->quotation_id)
                ->update(['status' => 7]);
        }

        SettingHelper::generateAndSaveNextInvoiceNumber($this->quotation->company_id);

        if ($this->quotation->quotation_id) {
            SettingHelper::leadActivityLog(8, $this->quotation->lead_id, null);
            SettingHelper::InvEstActivityLog(18, null, $this->quotation->quotation_id, null);
        }
        SettingHelper::InvEstActivityLog(11, $invoice->id, null, null);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
        $this->redirect(route($this->route . '.view-invoice', $invoice->uuid));
    }
    
    // NEW DEV TAX RELATED 
    public function updatedIsTax()
    {
        if (!$this->tax_id && $this->is_tax) {
            $this->showTaxModel = true;
        } else {
            $this->recordTax();
        }
    }
    public function openModel()
    {
        $this->showTaxModel = !$this->showTaxModel;
        $this->is_tax = false;
        $this->resetErrorBag();
    }
    public function recordTax()
    {
        $this->validate([
            'tax_id' => 'required'
        ]);
        $this->tax_amount = 500;
        $this->sub_total = $this->grand_total - $this->tax_amount;
        $this->showTaxModel = false;
    }
}

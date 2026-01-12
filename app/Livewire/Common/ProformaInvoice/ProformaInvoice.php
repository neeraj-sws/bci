<?php

namespace App\Livewire\Common\ProformaInvoice;

use App\Helpers\SettingHelper;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\ProformaInvoicePayments;
use App\Models\ProformaInvoices;
use App\Models\Quotations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncomeExpenses;

#[Layout('components.layouts.common-app')]
class ProformaInvoice extends Component
{
    use WithPagination;

    public $pageTitle;
    public $search = '';
    public $estimateSettings;

    public $statusFilter = null;
    public $startdate, $enddate;
    public $route;
    public $showModal = false, $leads;
    public $companies, $company_id;


    // NEW DEV 
    public $payment_date, $paid_amount,$record_amount, $payment_method="1", $reference, $notes, $prinvoice;
    // 
    public $track_id = 1; public $is_copy = false; 
    public function mount()
    {
        $this->route = 'common';
        $this->pageTitle = 'Proforma ';
        $this->companies = Companies::select('company_id', 'company_name', 'company_email')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->company_name . ' - ' . $tourist->company_email];
            })
            ->toArray();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
    }

    public function render()
    {
        $query = ProformaInvoices::query();

        if ($this->statusFilter !== null) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search !== '') {
            $query->where('proforma_invoice_no', 'like', "%{$this->search}%");
        }

        if ($this->company_id) {
            $query->where('company_id', $this->company_id);
        }


        if ($this->startdate && $this->enddate) {
            $query->whereBetween('proforma_invoice_date', [$this->startdate, $this->enddate]);
        } elseif ($this->startdate) {
            $query->whereDate('proforma_invoice_date', '>=', $this->startdate);
        } elseif ($this->enddate) {
            $query->whereDate('proforma_invoice_date', '<=', $this->enddate);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        $counts = [
            'draft' => ProformaInvoices::where('status', 0)->count(),
            'sent' => ProformaInvoices::where('status', 1)->count(),
            'paid' => ProformaInvoices::where('status', operator: 2)->count(),
            'all' => ProformaInvoices::count(),
        ];

        return view('livewire.common.proformainvoice.proformainvoice', compact('items', 'counts'));
    }
    
        public function markPaid($id)
    {
        ProformaInvoices::findOrFail($id)->update([
            'status' => 2
        ]);
    }
    
    
    // NEW DEV 
    public function showRecordPaymentModal($id)
    {
        $this->resetErrorBag();
        $this->reset(['payment_date', 'paid_amount', 'payment_method', 'reference', 'notes','record_amount']);
        $this->prinvoice = ProformaInvoices::findOrFail($id);
        $this->paid_amount =  $this->prinvoice->total_remaning_amount;
        if($this->prinvoice->currency_label == 'INR'){
            $this->is_copy = true;
        } else{
            $this->is_copy = false;
        }
        $this->dispatch('open-offcanvas');
    }

    public function recordPayment()
    {
        $this->validate([
            'payment_date' => 'required',
            'paid_amount' => 'required',
            'record_amount' => 'required',
            'payment_method' => 'nullable',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        if ($this->prinvoice->lead_id) {
            $lead = Leads::where('lead_id', $this->prinvoice->lead_id)->first();
            if ($lead->stage_id != 7) {
                $lead->stage_id = 7;
                $lead->save();
            }
        }
        if ($this->prinvoice->quotation->quotation_id) {
            $remainigAmount = $this->prinvoice->quotation->total_remaning_amount - $this->paid_amount;

            if ($remainigAmount < 0) {
                $remainigAmount = 0;
            }

            Quotations::where('quotation_id', $this->prinvoice->quotation->quotation_id)->update(
                [
                    "total_paid_amount" => $this->prinvoice->quotation->total_paid_amount + $this->paid_amount,
                    "total_remaning_amount" => $remainigAmount,
                ]
            );
        }
        // 
        
        // NEW EXTRA DEV 
        
        if ($this->paid_amount > 0) {
            IncomeExpenses::create([
                'entry_type' => 2,
                'date' => $this->payment_date,
                // 'amount' => $this->paid_amount,
                'amount' => $this->record_amount,
                'reference' => $this->prinvoice->proforma_invoice_no . ' | ' . $this->prinvoice->proforma_invoice_title,
                'tourist_id' => $this->prinvoice->tourist_id,
                'tour_id' => $this->prinvoice->tour_id,
                'quotation_id' => $this->prinvoice->quotation_id,
                'proforma_invoice_id' => $this->prinvoice->proforma_invoice_id,
                'notes' => $this->notes,
                'payment_reference' => $this->reference,
                'category_id' => 5
            ]);
        }
        
        // ENDS HERE 


        if ($this->prinvoice->lead_id) {
            SettingHelper::leadActivityLog(33, $this->prinvoice->lead_id, null);
        }
        SettingHelper::InvEstActivityLog(33,  null, $this->prinvoice->quotation_id, null, null);
        SettingHelper::InvEstActivityLog(33,  null, null, null, $this->prinvoice->proforma_invoice_id);

        if ($this->prinvoice->total_remaning_amount - $this->paid_amount <= 0) {
            $this->prinvoice->status = 2;
            if ($this->prinvoice->lead_id) {
                SettingHelper::leadActivityLog(32, $this->prinvoice->lead_id, null);
            }
            SettingHelper::InvEstActivityLog(32,  null, $this->prinvoice->quotation_id, null, null);
            SettingHelper::InvEstActivityLog(32,  null, null, null, $this->prinvoice->proforma_invoice_id);
        } else {
            $this->prinvoice->status = 3;
        }
        if ($this->prinvoice->total_remaning_amount) {
            $this->prinvoice->total_paid_amount += $this->paid_amount;
            $this->prinvoice->total_remaning_amount -= $this->paid_amount;
        }
        $this->prinvoice->save();
        ProformaInvoicePayments::create([
            'proforma_invoice_id' => $this->prinvoice->proforma_invoice_id,
            'quotation_id' => $this->prinvoice->quotation_id,
            'payment_date' => $this->payment_date,
            'paid_amount' => $this->paid_amount,
            'record_amount' => $this->record_amount,
            'payment_method' => $this->payment_method,
            'reference' => $this->reference,
            'notes' => $this->notes,
        ]);
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Payment recorded successfully.'
        ]);
        $this->dispatch('close-offcanvas');
        $this->reset(['payment_date', 'paid_amount', 'payment_method', 'reference', 'notes']);
    }
    // 
}

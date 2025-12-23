<?php

namespace App\Livewire\Common\ProformaInvoice;

use App\Helpers\SettingHelper;
use App\Models\InvoiceSettings;
use App\Models\Leads;
use App\Models\ProformaInvoicePayments;
use App\Models\ProformaInvoices;
use App\Models\Quotations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, On, Rule};
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('components.layouts.common-app')]
class AddProformaInvoice extends Component
{
    public $pageTitle = "Proforma Invoice", $showModal = false;

    public $route;
    public $quotation_id, $quotation, $prinvoiceSettings;
    public $prinvoice_date, $expiry_date;

    // AMOUNT 
    public $toatl_amount, $total_remaning_amount, $total_paid_amount;

    // MODEL 
    public $amount;
    public $pay_amount;

    public $pr_no;

    public $isClear = false, $discount_amount, $sub_amount;


    public function loadQuotation($quotation_id = null)
    {
        $this->quotation = Quotations::with('items')
            ->where('uuid', $quotation_id)
            ->firstOrFail();

        if ($this->quotation) {
            $this->total_remaning_amount = $this->quotation->total_remaning_amount ?? 0;
            $this->total_paid_amount = $this->quotation->total_paid_amount ?? 0;
            $this->toatl_amount = $this->quotation->amount;
            $this->quotation_id = $this->quotation->id;
            $this->amount = $this->total_remaning_amount;
            $this->pay_amount = $this->total_remaning_amount;
        }

        // NEXT NUMBER LOGIC 
        if ($this->quotation->lastprinvoice) {
            $quotation_number = $this->quotation->lastprinvoice->proforma_invoice_no;
            if (preg_match('/-(\d+)$/', $quotation_number, $matches)) {
                $last_number = (int) $matches[1] + 1;
                $new_quotation_number = preg_replace('/-(\d+)$/', '-' . $last_number, $quotation_number);
            }
        } else {
            $new_quotation_number = $this->quotation->quotation_no . '-1';
        }
        $this->pr_no = $new_quotation_number;
    }

    public function mount($quotation_id = null)
    {
        $this->route = 'common';

        if ($quotation_id) {
            $this->loadQuotation($quotation_id);
        }
        $this->expiry_date = Carbon::now()->addDays(4)->format('Y-m-d');
        $this->prinvoice_date = Carbon::now()->format('Y-m-d');
        $this->prinvoiceSettings = InvoiceSettings::where('company_id', $this->quotation->company_id)->first();
    }
    public function render()
    {
        return view('livewire.common.proformainvoice.proformainvoice-add');
    }

    public function recordPayment()
    {
        $this->showModal = !$this->showModal;
    }
    public function recordPaymentStore()
    {
        $this->validate([
            'pay_amount' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if ($value > $this->toatl_amount) {
                    $fail('The payment amount cannot be greater than the total amount.');
                }
            }],
        ]);
        if ($this->pay_amount !== 0) {
            $this->isClear = false;
            $this->discount_amount = 0;
            $this->sub_amount = 0;
        }
        $this->amount = $this->pay_amount;
        $this->showModal = !$this->showModal;
    }

    public function addPrInvoice()
    {
        $pr_invoice = ProformaInvoices::create([
            'proforma_invoice_no' => $this->pr_no ? $this->pr_no : $this->quotation->quotation_no . '-1',
            'proforma_invoice_title' => $this->quotation->quotation_title,
            'proforma_invoice_date' => $this->prinvoice_date,
            'tour_id' =>  $this->quotation->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->amount,
            'lead_id' => $this->quotation->lead_id,
            'tourist_id' => $this->quotation->tourist_id,
            'user_id' => Auth::guard('web')->user()->id,
            'company_id' => $this->quotation->company_id,
            'quotation_id' => $this->quotation->quotation_id,
            'currency_label' => $this->quotation->currency_label,
            'discount_amount' => $this->discount_amount,
            'sub_amount' => $this->sub_amount,
        ]);
        $leadUuid = Str::uuid()->toString() . '-' . $pr_invoice->id . '-' . Str::uuid()->toString();
        $encodedUuid = base64_encode($leadUuid);
        $pr_invoice->uuid = $encodedUuid;
        if ($this->isClear) {
            $pr_invoice->status = 2;
        }
        $pr_invoice->save();
        if ($this->quotation->lead_id) {
            $lead = Leads::findOrFail($this->quotation->lead_id);
            $lead->update([
                "stage_id" => $this->isClear ? 7 : 8
            ]);
        }
        if ($this->isClear && $this->quotation->quotation_id) {
            $remainigAmount = $this->isClear ? 0 : $this->quotation->total_remaning_amount - $this->amount;
            
            // NEW DEV 
            $discount = $this->isClear
                ? ($this->quotation->discount_amount + $this->quotation->total_remaning_amount)
                : $this->quotation->discount_amount;
            $totalAmount = $this->isClear ? ($this->quotation->amount - $this->quotation->total_remaning_amount) : $this->quotation->amount;
            // 
            Quotations::where('quotation_id', $this->quotation->quotation_id)->update(
                [
                    "total_paid_amount" => $this->quotation->total_paid_amount + $this->amount,
                    "total_remaning_amount" => $remainigAmount,
                    // NEW DEV 
                    "discount_amount" => $discount ?? $this->quotation->discount_amount,
                    "amount" => $totalAmount,
                    // 
                    "status" => 6
                ]
            );
        }else {
            Quotations::where('quotation_id', $this->quotation->quotation_id)->update(
                [
                    "status" => 6
                ]
            );
        }
        ProformaInvoicePayments::create([
            "proforma_invoice_id"  => $pr_invoice->id,
            "quotation_id" => $pr_invoice->quotation_id,
            "paid_amount" => $this->amount
        ]);
        SettingHelper::InvEstActivityLog(31, null, null, Auth::guard('web')->user()->id, $pr_invoice->proforma_invoice_id);
        SettingHelper::InvEstActivityLog(31, null, $this->quotation->quotation_id, Auth::guard('web')->user()->id, null);
        SettingHelper::leadActivityLog(31, $this->quotation->lead_id, Auth::guard('web')->user()->id);
        if ($this->isClear) {
            SettingHelper::InvEstActivityLog(32, $pr_invoice->proforma_invoice_id, null, Auth::guard('web')->user()->id, null);
            SettingHelper::InvEstActivityLog(32, null, $this->quotation->quotation_id, Auth::guard('web')->user()->id, null);
            SettingHelper::leadActivityLog(32, $this->quotation->lead_id, Auth::guard('web')->user()->id);
        }
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
        $this->redirect(route($this->route . '.view-proformainvoice', $pr_invoice->uuid), navigate: true);
    }

    public function updatedPayAmount($amount)
    {
        if ($amount == 0) {
            $this->updatedFiles();
        }
    }

    public function updatedFiles()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Amount is Zero!',
            'text' => 'The remaining balance will be discounted and the invoice will be marked as paid. Please type "discount" to confirm.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, mark as paid',
            'cancelButtonText' => 'Cancel',
            'action' => 'confirmMarkAsPaid',
            'cancelAction' => 'cancelMarkAsPaid',
            'confirmText' => 'discount', // ✅ user must type this
        ]);
    }
    #[On('confirmMarkAsPaid')]
    public function confirmMarkAsPaid()
    {
        $this->isClear = true;
        $this->discount_amount = $this->total_remaning_amount;
        $this->sub_amount = $this->total_remaning_amount;

        $this->amount = $this->pay_amount;
        $this->showModal = !$this->showModal;
    }
    #[On('cancelMarkAsPaid')]
    public function cancelMarkAsPaid()
    {
        $this->pay_amount =  $this->total_remaning_amount;
    }
}

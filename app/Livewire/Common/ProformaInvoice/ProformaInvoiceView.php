<?php

namespace App\Livewire\Common\ProformaInvoice;

use App\Helpers\SettingHelper;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination, WithFileUploads};
use App\Models\Companies;
use App\Models\InvoiceSettings;
use App\Models\Leads;
use App\Models\ProformaInvoices;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Quotations;
use App\Models\ProformaInvoicePayments;
use App\Models\IncomeExpenses;

#[Layout('components.layouts.common-app')]
class ProformaInvoiceView extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $prinvoice;
    public $prinvoiceSettings;
    public $genrealSettings;
    public $historys;
    public $organization_name;
    public $route;

    // NEW DEV 
    public $showTourModal = false; // control modal visibility
    public $attachment;
    public $existingImage, $tour, $is_attachment;

    // NEW DEV 
    public $payment_date, $paid_amount, $payment_method="1", $reference, $notes,$recordPaymentHistory = [];
    // 
    
    public function mount($id)
    {
        $this->route = 'common';

        $this->prinvoice = ProformaInvoices::where('uuid', $id)->firstOrFail() ?? [];
        $this->genrealSettings = GeneralSettings::where('company_id', $this->prinvoice?->company_id)->first();
        $this->organization_name = Companies::where('company_id', $this->prinvoice?->company_id)->first()->company_name;
        $this->prinvoiceSettings = InvoiceSettings::where('company_id', $this->prinvoice?->company_id)->first();
    }
    public function render()
    {
        $this->historys = InvEstActivity::where('proforma_invoice_id', $this->prinvoice->id)->orderBy('inv_est_activity_id', 'DESC')->get();
        $this->recordPaymentHistory = ProformaInvoicePayments::where('proforma_invoice_id', $this->prinvoice->id)
            ->orderBy('proforma_invoice_payment_id', 'DESC')
            ->get();
        return view('livewire.common.proformainvoice.proformainvoice-view');
    }


    public function confirmupdatePr()
    {
        $this->paid_amount =  $this->prinvoice->total_remaning_amount;
        $this->dispatch('open-offcanvas');
        // $this->dispatch('swal:confirm', [
        //     'title' => 'Confirm Paid!',
        //     'text' => "The proforma invoice is paid?",
        //     'icon' => 'warning',
        //     'showCancelButton' => true,
        //     'confirmButtonText' => 'Yes, Mark as Paid',
        //     'cancelButtonText' => 'Cancel',
        //     'action' => 'updatePr',
        // ]);
    }
    #[On('updatePr')]
    public function updatePr()
    {
        // NEW DEV 
        if ($this->prinvoice->lead_id) {
            $lead = Leads::where('lead_id', $this->prinvoice->lead_id)->first();
            if ($lead->stage_id != 7) {
                $lead->stage_id = 7;
                $lead->save();
            }
        }
        // 
        
        // NEW DEV 
        if ($this->prinvoice->quotation->quotation_id) {
            $remainigAmount = $this->prinvoice->quotation->total_remaning_amount - $this->prinvoice->amount;
            Quotations::where('quotation_id', $this->prinvoice->quotation->quotation_id)->update(
                [
                    "total_paid_amount" => $this->prinvoice->quotation->total_paid_amount + $this->prinvoice->amount,
                    "total_remaning_amount" => $remainigAmount,
                ]
            );
        }
        // 
        
        
        $this->prinvoice->status = 2;
        $this->prinvoice->save();
        if ($this->prinvoice->lead_id) {
            SettingHelper::leadActivityLog(32, $this->prinvoice->lead_id, null);
        }
        SettingHelper::InvEstActivityLog(32,  null, $this->prinvoice->quotation_id, null, null);
        SettingHelper::InvEstActivityLog(32,  null, null, null, $this->prinvoice->proforma_invoice_id);
    }

    // NEW DEV 
    public function openModel()
    {
        if ($this->prinvoice->tour_id) {
            $this->tour  = $this->prinvoice->tour;
            if ($this->tour->attachment) {
                $this->existingImage = $this->tour->attachment;
            }
            $this->showTourModal = true;
        } else {
            $this->senPrInvoice();
        }
    }
    public function closeModel()
    {
        $this->showTourModal = false;
        $this->existingImage = null;
        $this->attachment = null;
        $this->is_attachment = null;
    }
    public function sendAttachment()
    {
        $this->validate([
            'is_attachment' => 'nullable'
        ]);

        if ($this->is_attachment) {
            if (!$this->attachment && !$this->existingImage) {
                $this->addError('attachment', 'Please upload an attachment or use the existing one.');
                return;
            }
        }

        $encodedId = base64_encode($this->prinvoice->id);
        $estimatUrl = env('APP_URL') . '/proformainvoice-portal/' . $encodedId;

        $variables = [
            '[prinvoice-date]' => Carbon::parse($this->prinvoice->proforma_invoice_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[prinvoice-number]'      => $this->prinvoice->proforma_invoice_no,

            '[expiry-date]' => Carbon::parse($this->prinvoice->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[prinvoice-title]'       => $this->prinvoice->proforma_invoice_title,
            '[client-name]'          => $this->prinvoice->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->prinvoice->tourist->primary_contact ?? '',
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->prinvoice->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->prinvoice?->currency_label,
            '[prinvoice-url]'         => $estimatUrl,
        ];
        $attachmentPath = null;

        if ($this->attachment && $this->is_attachment) {
            $path = "uploads/proformainvoice/{$this->prinvoice->id}/attachment";
            if (!Storage::disk('public_root')->exists($path)) {
                Storage::disk('public_root')->makeDirectory($path);
            }

            $name = pathinfo($this->attachment->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $this->attachment->getClientOriginalExtension();
            $fileName = "$name.$ext";
            $files = collect(Storage::disk('public_root')->files($path));
            $matching = $files->filter(function ($f) use ($name, $ext) {
                return preg_match("/^" . preg_quote($name, '/') . "(\(\d+\))?\.$ext$/", basename($f));
            });
            if ($matching->isNotEmpty()) {
                $fileName = "{$name}(" . $matching->count() . ").{$ext}";
            }
            $this->attachment->storeAs($path, $fileName, 'public_root');
            $attachmentPath = $path . '/' . $fileName;
            $this->existingImage = $attachmentPath;
        } elseif ($this->existingImage && $this->is_attachment) {
            $attachmentPath = $this->existingImage;
        }


        // GENRETE PDF & SEND 
        $path = "uploads/proformainvoice/{$this->prinvoice->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        // $fileName = 'proformainvoice-' . $this->prinvoice->proforma_invoice_no . '.pdf';
        $fileName = $this->prinvoice->proforma_invoice_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.proformainvoice-pdf', [
            'prinvoice' => $this->prinvoice,
            'prinvoiceSettings' => $this->prinvoiceSettings,
             'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 

        $result = SettingHelper::sendEmail(
            '3',
            $this->prinvoice->company_id,
            $variables,
            $this->prinvoice->tourist->contact_email,
            $this->organization_name,
            $attachmentPath,
            $filePath
        );

        if ($result['status'] === 'success') {
            if($this->prinvoice->status == 0){
                    $this->prinvoice->update([
                        "status" => 1,
                    ]);
            }
            
            $this->prinvoice->update([
                "is_attachment" => 1,
                "attachment" => $attachmentPath
            ]);

            $this->reset(['showTourModal', 'existingImage', 'attachment', 'is_attachment']);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Proforma Invoice sent successfully.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => $result['message']
            ]);
        }
    }
    public function senPrInvoice()
    {
        $encodedId = base64_encode($this->estimate->id);
        $estimatUrl = env('APP_URL') . '/proformainvoice-portal/' . $encodedId;

        $variables = [
            '[prinvoice-date]' => Carbon::parse($this->prinvoice->proforma_invoice_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[prinvoice-number]'      => $this->prinvoice->proforma_invoice_no,

            '[expiry-date]' => Carbon::parse($this->prinvoice->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[prinvoice-title]'       => $this->prinvoice->proforma_invoice_title,
            '[client-name]'          => $this->prinvoice->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->prinvoice->tourist->primary_contact ?? '',
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->prinvoice->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->prinvoice?->currency_label,
            '[prinvoice-url]'         => $estimatUrl,
        ];
        // GENRETE PDF & SEND 
        $path = "uploads/proformainvoice/{$this->prinvoice->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        // $fileName = 'proformainvoice-' . $this->prinvoice->proforma_invoice_no . '.pdf';
         $fileName = $this->prinvoice->proforma_invoice_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.proforma-invoice-pdf', [
            'estimate' => $this->estimate,
            'estimateSettings' => $this->estimateSettings,
             'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 
        $result = SettingHelper::sendEmail(
            '3',
            $this->prinvoice->company_id,
            $variables,
            $this->prinvoice->tourist->contact_email,
            $this->organization_name,
            $filePath
        );
        if ($result['status'] === 'success') {
            if($this->prinvoice->status == 0){
                $this->prinvoice->update([
                    "status" => 1
                ]);
            }
       
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Proforma Invoice sent successfully.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => $result['message']
            ]);
        }
    }
    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, Accepted it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'delete'
        ]);
    }
    public function handleSwalConfirm()
    {
        $this->updateEstimate(2);
    }
    public function confirmDiscard($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, Accepted it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'discard'
        ]);
    }
    public function handleSwalDiscard()
    {
        $this->updateEstimate(3);
    }
    public function updatedAttachment($data)
    {
        if ($this->attachment) {
            $fileName = $this->attachment->getClientOriginalName();
            $path = "uploads/proformainvoice/{$this->prinvoice->id}/attachment/" . $fileName;
            if (Storage::disk('public_root')->exists($path)) {
                $this->dispatch('swal:confirm', [
                    'title' => 'File already exists!',
                    'text' => "The file '{$fileName}' already exists. Do you want to replace it?",
                    'icon' => 'warning',
                    'showCancelButton' => true,
                    'confirmButtonText' => 'Yes, replace it',
                    'cancelButtonText' => 'Cancel',
                    'action' => 'confirmReplace',
                    'cancelAction' => 'cancelReplace',
                ]);
                return;
            }
        }
    }
    #[On('confirmReplace')]
    public function confirmReplace()
    {
        if ($this->attachment) {
            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File was added with (1)."
            ]);
        }
    }
    #[On('cancelReplace')]
    public function cancelReplace()
    {
        if ($this->attachment) {
            $removeName = $this->attachment->getClientOriginalName();
            $this->attachment = null;
            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File '{$removeName}' was not added."
            ]);
        }
    }
    
    // NEW DEV 
    public function recordPayment()
    {
        $this->validate([
            'payment_date' => 'required',
            'paid_amount' => 'required',
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
                'amount' => $this->paid_amount,
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
        }else{
             $this->prinvoice->status = 3;
        }
        if ($this->prinvoice->total_remaning_amount) {
            $this->prinvoice->total_paid_amount += $this->paid_amount;
            $newRemaining = $this->prinvoice->total_remaning_amount - $this->paid_amount;
            if ($newRemaining < 0) {
                $newRemaining = 0;
            }
            $this->prinvoice->total_remaning_amount = $newRemaining;
        }
        $this->prinvoice->save();
        ProformaInvoicePayments::create([
            'proforma_invoice_id' => $this->prinvoice->proforma_invoice_id,
            'quotation_id' => $this->prinvoice->quotation_id,
            'payment_date' => $this->payment_date,
            'paid_amount' => $this->paid_amount,
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
    
        public function markasPaid()
    {
        $this->prinvoice->status = 1;
        $this->prinvoice->save();
                    SettingHelper::InvEstActivityLog(34,  null, null, null, $this->prinvoice->proforma_invoice_id);
    }
    
}

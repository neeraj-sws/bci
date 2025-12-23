<?php

namespace App\Livewire\Common\Quotations;

use App\Helpers\SettingHelper;
use App\Models\Quotations;
use App\Models\QuotationSettings;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination, WithFileUploads};
use App\Models\Companies;
use App\Models\ProformaInvoicePayments;
use App\Models\ProformaInvoices;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\IncomeExpenses;

#[Layout('components.layouts.common-app')]
class QuotationView extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $estimate;
    public $estimateSettings;
    public $genrealSettings;
    public $historys;
    public $organization_name;
    public $route;

    protected $listeners = ['delete' => 'handleSwalConfirm', 'discard' => 'handleSwalDiscard'];

    // NEW DEV 
    public $showTourModal = false; // control modal visibility
    public $showRecipiets = false, $prInvoices; // control modal visibility
    public $attachment;
    public $existingImage, $tour, $is_attachment;
    
    public $totalExpense=0;

    public function mount($id)
    {
        $this->route = 'common';

        $this->estimate = Quotations::where('uuid', $id)->firstOrFail() ?? [];
        $this->estimateSettings = QuotationSettings::where('company_id', $this->estimate?->company_id)->first();
        $this->genrealSettings = GeneralSettings::where('company_id', $this->estimate?->company_id)->first();
        $this->organization_name = Companies::where('company_id', $this->estimate?->company_id)->first()->company_name;
        
        $this->prInvoices = ProformaInvoices::where('quotation_id', $this->estimate->id)
            ->orderBy('created_at', 'DESC')
            ->get();
            
               $this->totalExpense = IncomeExpenses::where('quotation_id',$this->estimate->id)->where('soft_delete',0)->where('entry_type',1)->sum('amount');
            
    }
    public function render()
    {
        $this->historys = InvEstActivity::where('quotation_id', $this->estimate->id)->orderBy('inv_est_activity_id', 'DESC')->get();
            
        return view('livewire.common.quotations.quotation-view');
    }
    public function updateEstimate($value)
    {
        $this->estimate->status = $value;
        $this->estimate->save();



        if ($value == 1) {

            if ($this->estimate->lead_id) {
                SettingHelper::leadActivityLog(19, $this->estimate->lead_id, null);
            }

            SettingHelper::InvEstActivityLog(15,  null, $this->estimate->id, null);
        } elseif ($value == 2) {

            if ($this->estimate->revised_no) {

                Quotations::where(function ($q) {
                    $q->where('revised_no', $this->estimate->revised_no)
                        ->orWhere('quotation_no', $this->estimate->revised_no);
                })
                    ->where('quotation_id', '!=', $this->estimate->id)
                    ->update(['status' => 5]);
            }

            if ($this->estimate->lead_id) {
                SettingHelper::leadActivityLog(29, $this->estimate->lead_id, null);
            }
            SettingHelper::InvEstActivityLog(27,  null, $this->estimate->id, null);
        } elseif ($value == 3) {

            if ($this->estimate->lead_id) {
                SettingHelper::leadActivityLog(30, $this->estimate->lead_id, null);
            }

            SettingHelper::InvEstActivityLog(28,  null, $this->estimate->id, null);
        }
    }
    // NEW DEV 
    public function openModel()
    {
        if ($this->estimate->tour_id) {
            $this->tour  = $this->estimate->tour;
            if ($this->tour->attachment) {
                $this->existingImage = $this->tour->attachment;
            }
            $this->showTourModal = true;
        } else {
            $this->senEstimate();
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

        $encodedId = base64_encode($this->estimate->id);
        $estimatUrl = env('APP_URL') . '/quotation-portal/' . $encodedId;

        $variables = [
            // '[estimate-date]'        => $this->estimate->quotation_date,
            '[estimate-date]' => Carbon::parse($this->estimate->quotation_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[estimate-number]'      => $this->estimate->quotation_no,
            '[po-number]'            => $this->estimate->po_number,
            // '[expiry-date]'          => $this->estimate->expiry_date,

            '[expiry-date]' => Carbon::parse($this->estimate->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[estimate-title]'       => $this->estimate->quotation_title,
            '[estimate-notes]'       => $this->estimate->notes,
            '[client-name]'          => $this->estimate->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->estimate->tourist->primary_contact ?? '',
            // '[total-amount]'         => $this->estimate->amount,
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->estimate->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->estimate?->currency_label,
            '[estimate-url]'         => $estimatUrl,
            '[due-date]'             => $this->estimate->expiry_date,
        ];

        $attachmentPath = null;

        if ($this->attachment && $this->is_attachment) {
            $path = "uploads/quotations/{$this->estimate->id}/attachment";
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
        $path = "uploads/quotations/{$this->estimate->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        $fileName = 'quotation-' . $this->estimate->quotation_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.estimate-pdf', [
            'estimate' => $this->estimate,
            'estimateSettings' => $this->estimateSettings,
            'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 

        $result = SettingHelper::sendEmail(
            '2',
            $this->estimate->company_id,
            $variables,
            $this->estimate->tourist->contact_email,
            $this->organization_name,
            $attachmentPath,
            $filePath
        );

        if ($result['status'] === 'success') {
            if($this->estimate->status == 0){
                $this->estimate->update([
                    "status" => 1,
                ]);
            }
            $this->estimate->update([
                "is_attachment" => 1,
                "attachment" => $attachmentPath
            ]);
            $this->reset(['showTourModal', 'existingImage', 'attachment', 'is_attachment']);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Estimate sent successfully.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => $result['message']
            ]);
        }
    }
    public function senEstimate()
    {
        $encodedId = base64_encode($this->estimate->id);
        $estimatUrl = env('APP_URL') . '/quotation-portal/' . $encodedId;

        $variables = [
            // '[estimate-date]'        => $this->estimate->quotation_date,
            '[estimate-date]' => Carbon::parse($this->estimate->quotation_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[estimate-number]'      => $this->estimate->quotation_no,
            '[po-number]'            => $this->estimate->po_number,
            // '[expiry-date]'          => $this->estimate->expiry_date,

            '[expiry-date]' => Carbon::parse($this->estimate->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[estimate-title]'       => $this->estimate->quotation_title,
            '[estimate-notes]'       => $this->estimate->notes,
            '[client-name]'          => $this->estimate->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->estimate->tourist->primary_contact ?? '',
            // '[total-amount]'         => $this->estimate->amount,
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->estimate->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->estimate?->currency_label,
            '[estimate-url]'         => $estimatUrl,
            '[due-date]'             => $this->estimate->expiry_date,
        ];

        // GENRETE PDF & SEND 
        $path = "uploads/quotations/{$this->estimate->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        $fileName = 'quotation-' . $this->estimate->quotation_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.estimate-pdf', [
            'estimate' => $this->estimate,
            'estimateSettings' => $this->estimateSettings,
            'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 
        $result = SettingHelper::sendEmail(
            '2',
            $this->estimate->company_id,
            $variables,
            $this->estimate->tourist->contact_email,
            $this->organization_name,
            $filePath
        );
        if ($result['status'] === 'success') {
            if($this->estimate->status == 0){
                $this->estimate->update([
                    "status" => 1
                ]);
            }
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Estimate sent successfully.'
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
            $path = "uploads/quotations/{$this->estimate->id}/attachment/" . $fileName;
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

    public function openRecipiets()
    {
        $this->showRecipiets = !$this->showRecipiets;
        $this->prInvoices = ProformaInvoicePayments::where('quotation_id', $this->estimate->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
        public function convertProformInvoice()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Convert to Proforma Invoice!',
            'text' => "This action cannot be undone.",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, convert it',
            'cancelButtonText' => 'Cancel',
            'action' => 'confirmConvert',
            'cancelAction' => 'cancelReplace',
        ]);
    }
    #[On('confirmConvert')]
    public function confirmConvert()
    {
        $this->redirect(route($this->route . '.add-proformainvoice', $this->estimate->uuid));
    }
    
        public function convertInvoice()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Convert to Invoice!',
            'text' => "This action cannot be undone.",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, convert it',
            'cancelButtonText' => 'Cancel',
            'action' => 'confirmInvoiceConvert',
            'cancelAction' => 'cancelReplace',
        ]);
    }
    #[On('confirmInvoiceConvert')]
    public function confirmInvoiceConvert()
    {
        $this->redirect(route($this->route . '.add-invoice', $this->estimate->uuid));
    }
}

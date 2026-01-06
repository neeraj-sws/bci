<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Companies;
use App\Models\OrganizationSetting;
use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination, WithFileUploads};

#[Layout('components.layouts.common-app')]
class InvoiceView extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $invoice;
    public $invoiceSettings;
    public $genrealSettings;
    public $historys;
    public $organization_name;
    public $route;

    public $showTourModal = false;
    public $attachment;
    public $existingImage, $tour, $is_attachment;

    public function mount($id)
    {
        $this->route = 'common';
        $this->invoice = Invoices::where('uuid', $id)->firstOrFail() ?? [];
        $this->genrealSettings = GeneralSettings::where('company_id', $this->invoice?->company_id)->first();
        $this->organization_name = Companies::where('company_id', $this->invoice?->company_id)->first()->company_name;
        $this->invoiceSettings = InvoiceSettings::where('company_id', $this->invoice?->company_id)->first();
    }
    public function render()
    {
        $this->historys = InvEstActivity::where('invoice_id', $this->invoice->id)->orderBy('inv_est_activity_id', 'DESC')->get();
        return view('livewire.common.invoices.invoice-view');
    }
    // NEW DEV 
    public function openModel()
    {
        if ($this->invoice->tour_id) {
            $this->tour  = $this->invoice->tour;
            if ($this->tour->attachment) {
                $this->existingImage = $this->tour->attachment;
            }
            $this->showTourModal = true;
        } else {
            $this->senInvoice();
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

        $encodedId = base64_encode($this->invoice->id);
        $invoiceUrl = env('APP_URL') . '/invoice-portal/' . $encodedId;

        $variables = [
            '[invoice-date]' => Carbon::parse($this->invoice->invoice_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[invoice-number]'      => $this->invoice->invoice_no,

            '[expiry-date]' => Carbon::parse($this->invoice->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[invoice-title]'       => $this->invoice->invoice_title,
            '[client-name]'          => $this->invoice->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->invoice->tourist->primary_contact ?? '',
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->invoice->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->invoice?->currency_label,
            '[invoice-url]'         => $invoiceUrl,
        ];
        $attachmentPath = null;

        if ($this->attachment && $this->is_attachment) {
            $path = "uploads/invoice/{$this->invoice->id}/attachment";
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
        $path = "uploads/invoice/{$this->invoice->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        // $fileName = 'invoice-' . $this->invoice->invoice_no . '.pdf';
        $fileName = $this->invoice->invoice_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.invoice-pdf', [
            'invoice' => $this->invoice,
            'invoiceSettings' => $this->invoiceSettings,
             'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 

        $result = SettingHelper::sendEmail(
            '1',
            $this->invoice->company_id,
            $variables,
            $this->invoice->tourist->contact_email,
            $this->organization_name,
            $attachmentPath,
            $filePath
        );

        if ($result['status'] === 'success') {
            if ($this->invoice->status == 0) {
                $this->invoice->update([
                    "status" => 1,
                ]);
            }
            $this->invoice->update([
                "is_attachment" => 1,
                "attachment" => $attachmentPath
            ]);
            $this->reset(['showTourModal', 'existingImage', 'attachment', 'is_attachment']);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Invoice sent successfully.'
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
        $encodedId = base64_encode($this->invoice->id);
        $invoiceUrl = env('APP_URL') . '/invoice-portal/' . $encodedId;

        $variables = [
            '[invoice-date]' => Carbon::parse($this->invoice->invoice_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[invoice-number]'      => $this->invoice->invoice_no,

            '[expiry-date]' => Carbon::parse($this->invoice->expiry_date)
                ->format($this->genrealSettings->date_format ?? 'd M Y'),

            '[invoice-title]'       => $this->invoice->invoice_title,
            '[client-name]'          => $this->invoice->tourist->primary_contact,
            '[organization-name]'    => $this->organization_name,
            '[client-contact-name]'  => $this->invoice->tourist->primary_contact ?? '',
            '[total-amount]'        => \App\Helpers\SettingHelper::formatCurrency(
                $this->invoice->amount ?? 0,
                $this->genrealSettings->number_format
            ) . ' ' . $this->invoice?->currency_label,
            '[invoice-url]'         => $invoiceUrl,
        ];
        // GENRETE PDF & SEND 
        $path = "uploads/nvoice/{$this->invoice->id}/pdf";
        if (!Storage::disk('public_root')->exists($path)) {
            Storage::disk('public_root')->makeDirectory($path);
        }
        // $fileName = 'invoice-' . $this->invoice->invoice_no . '.pdf';
        $fileName = $this->invoice->invoice_no . '.pdf';
        $filePath = "{$path}/{$fileName}";
        if (Storage::disk('public_root')->exists($filePath)) {
            Storage::disk('public_root')->delete($filePath);
        }
        $pdf = Pdf::loadView('livewire.common.invoice-pdf', [
            'invoice' => $this->estimate,
            'invoiceSettings' => $this->estimateSettings,
             'showStatus' => false
        ])->setPaper('a4');
        Storage::disk('public_root')->put($filePath, $pdf->output());
        // 
        $result = SettingHelper::sendEmail(
            '1',
            $this->invoice->company_id,
            $variables,
            $this->invoice->tourist->contact_email,
            $this->organization_name,
            $filePath
        );
        if ($result['status'] === 'success') {
            if ($this->invoice->status == 0) {
                $this->invoice->update([
                    "status" => 1
                ]);
            }
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'message' => 'Invoice sent successfully.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => $result['message']
            ]);
        }
    }
    public function updatedAttachment($data)
    {
        if ($this->attachment) {
            $fileName = $this->attachment->getClientOriginalName();
            $path = "uploads/invoice/{$this->invoice->id}/attachment/" . $fileName;
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
}

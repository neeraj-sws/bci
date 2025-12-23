<?php

namespace App\Livewire\Common;

use App\Models\InvoiceSettings;
use App\Models\Invoices;
use Livewire\Component;
use Livewire\Attributes\{On};
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdf extends Component
{
    public $invoice;
    public $invoiceSettings;

    public function mount($invoice, $invoiceSettings)
    {
        $this->invoice = $invoice;
        $this->invoiceSettings = $invoiceSettings;
    }

    #[On('refreshEstimatePdf')]
    public function render()
    {
        return view('livewire.common.invoice-pdf');
    }

    public function download($id)
    {
        $invoice = Invoices::with(['tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $invoiceSettings = InvoiceSettings::where('company_id', $invoice?->company_id)->first();

        $pdf = Pdf::loadView('livewire.common.invoice-pdf', [
            'invoice' => $invoice,
            'invoiceSettings' => $invoiceSettings,
            'showStatus' => false
        ])->setPaper('a4');

        // return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
        return $pdf->download($invoice->invoice_no . '.pdf');
    }

    public function preview($id)
    {
        $invoice = Invoices::with(['tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $invoiceSettings = InvoiceSettings::where('company_id', $invoice?->company_id)->first();

        $pdf = Pdf::loadView('livewire.common.invoice-pdf', [
            'invoice' => $invoice,
            'invoiceSettings' => $invoiceSettings,
            'showStatus' => true
        ])->setPaper('a4');
        return $pdf->stream('invoice-preview-' . $invoice->invoice_no . '.pdf');
    }
}

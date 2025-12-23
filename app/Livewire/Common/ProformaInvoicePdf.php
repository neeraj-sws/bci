<?php

namespace App\Livewire\Common;

use App\Models\InvoiceSettings;
use App\Models\ProformaInvoices;
use Livewire\Component;
use Livewire\Attributes\{On};
use Barryvdh\DomPDF\Facade\Pdf;

class ProformaInvoicePdf extends Component
{
    public $prinvoice;
    public $prinvoiceSettings;

    public function mount($prinvoice, $prinvoiceSettings)
    {
        $this->prinvoice = $prinvoice;
        $this->prinvoiceSettings = $prinvoiceSettings;
    }

    #[On('refreshEstimatePdf')]
    public function render()
    {
        return view('livewire.common.proformainvoice-pdf');
    }

    public function download($id)
    {
        $prinvoice = ProformaInvoices::with(['tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $prinvoiceSettings = InvoiceSettings::where('company_id', $prinvoice?->company_id)->first();

        $pdf = Pdf::loadView('livewire.common.proformainvoice-pdf', [
            'prinvoice' => $prinvoice,
            'prinvoiceSettings' => $prinvoiceSettings,
            'showStatus' => false
        ])->setPaper('a4');

        // return $pdf->download('prinvoice-' . $prinvoice->proforma_invoice_no . '.pdf');
         return $pdf->download($prinvoice->proforma_invoice_no . '.pdf');
    }

    public function preview($id)
    {
        $prinvoice = ProformaInvoices::with(['tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $prinvoiceSettings = InvoiceSettings::where('company_id', $prinvoice?->company_id)->first();

        $pdf = Pdf::loadView('livewire.common.proformainvoice-pdf', [
            'prinvoice' => $prinvoice,
            'prinvoiceSettings' => $prinvoiceSettings,
            'showStatus' => true
        ])->setPaper('a4');
        return $pdf->stream('prinvoice-preview-' . $prinvoice->proforma_invoice_no . '.pdf');
    }
}

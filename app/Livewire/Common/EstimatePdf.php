<?php

namespace App\Livewire\Common;

use App\Models\Quotations;
use App\Models\QuotationSettings;
use Livewire\Component;
use Livewire\Attributes\{Layout, On};
use Barryvdh\DomPDF\Facade\Pdf;

class EstimatePdf extends Component
{
    public $estimate;
    public $estimateSettings;

    public function mount($estimate, $estimateSettings)
    {
        $this->estimate = $estimate;
        $this->estimateSettings = $estimateSettings;
    }

    #[On('refreshEstimatePdf')]
    public function render()
    {
        return view('livewire.common.estimate-pdf');
    }

    public function download($id)
    {
        $estimate = Quotations::with(['items', 'tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $estimateSettings = QuotationSettings::where('company_id', $this->estimate?->company_id)->first();

        $pdf = Pdf::loadView('livewire.common.estimate-pdf', [
            'estimate' => $estimate,
            'estimateSettings' => $estimateSettings,
            'showStatus' => false
        ])->setPaper('a4');

        // return $pdf->download('quotation-' . $estimate->quotation_no . '.pdf');
         return $pdf->download($estimate->quotation_no . '.pdf');
    }

    public function preview($id)
    {
        $estimate = Quotations::with(['items', 'tourist', 'tour'])->where('uuid', $id)->firstOrFail();
        $estimateSettings = QuotationSettings::where('company_id', $this->estimate?->company_id)->first();

        // Load the PDF content but do not download it, just render it in browser
        $pdf = Pdf::loadView('livewire.common.estimate-pdf', [
            'estimate' => $estimate,
            'estimateSettings' => $estimateSettings,
            'showStatus' => true
        ])->setPaper('a4');

        // This will stream the PDF in the browser instead of downloading
        return $pdf->stream('quotation-preview-' . $estimate->quotation_no . '.pdf');
    }
}

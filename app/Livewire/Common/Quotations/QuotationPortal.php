<?php

namespace App\Livewire\Common\Quotations;

use App\Models\Quotations;
use App\Models\QuotationSettings;
use App\Models\GeneralSettings;
use Livewire\Attributes\{Layout};
use Livewire\{Component};

#[Layout('components.layouts.guest_login')]
class QuotationPortal extends Component
{
    public $estimate;
    public $estimateSettings;
    public $genrealSettings;


    public function mount($id)
    {
        $decodedId = base64_decode($id);
        $this->estimate = Quotations::where('quotation_id', $decodedId)->firstOrFail() ?? [];
        $this->estimateSettings = QuotationSettings::where('company_id', $this->estimate?->company_id)->first();
        $this->genrealSettings = GeneralSettings::where('company_id', $this->estimate?->company_id)->first();
    }
    public function render()
    {
        return view('livewire.common.quotations.quotation-portal');
    }
}

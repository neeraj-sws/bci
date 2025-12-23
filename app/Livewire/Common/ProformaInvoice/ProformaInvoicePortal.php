<?php

namespace App\Livewire\Common\ProformaInvoice;


use App\Models\GeneralSettings;
use App\Models\InvoiceSettings;
use App\Models\ProformaInvoices;
use Livewire\Attributes\{Layout};
use Livewire\{Component};

#[Layout('components.layouts.guest_login')]
class ProformaInvoicePortal extends Component
{
    public $prinvoice;
    public $prinvoiceSettings;
    public $genrealSettings;


    public function mount($id)
    {
        $decodedId = base64_decode($id);
        $this->prinvoice = ProformaInvoices::where('proforma_invoice_id', $decodedId)->firstOrFail() ?? [];
        $this->prinvoiceSettings = InvoiceSettings::where('company_id', $this->prinvoice?->company_id)->first();
        $this->genrealSettings = GeneralSettings::where('company_id', $this->prinvoice?->company_id)->first();
    }
    public function render()
    {
        return view('livewire.common.proformainvoice.proforma-portal');
    }
}

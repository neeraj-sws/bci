<?php

namespace App\Livewire\Common\Invoices;

use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\GeneralSettings;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component};

#[Layout('components.layouts.guest_login')]
class InvoicePortal extends Component
{
    public $invoice;
    public $invoiceSettings;
    public $genrealSettings;


    public function mount($id)
    {
        $decodedId = base64_decode($id);
        $this->invoice = Invoices::where('invoice_id', $decodedId)->firstOrFail() ?? [];
        $this->invoiceSettings = InvoiceSettings::where('company_id', $this->invoice?->company_id)->first();
        $this->genrealSettings = GeneralSettings::where('company_id', $this->invoice?->company_id)->first();
    }
    public function render()
    {
        return view('livewire.common.invoices.invoice-portal');
    }
}

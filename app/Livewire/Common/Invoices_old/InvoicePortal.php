<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.guest_login')]
class InvoicePortal extends Component
{
    use WithPagination;

    public $invoice;
    public $invoiceSettings;
    public $genrealSettings;

    public function mount($id)
    {
        $decodedId = base64_decode($id);
        $this->invoice = Invoices::find($decodedId) ?? [];
        $this->invoiceSettings = InvoiceSettings::first();
        $this->genrealSettings = GeneralSettings::first();
    }
    public function render()
    {
        return view('livewire.common.invoices.invoice-portal');
    }
}

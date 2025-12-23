<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\OrganizationSetting;
use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\GeneralSettings;
use App\Models\InvEstActivity;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class InvoiceView extends Component
{
    use WithPagination;

    public $invoice;
    public $invoiceSettings;
    public $genrealSettings;
    public $historys;
    public $organization_name;
    public $route;


    public function mount($id)
    {
        $this->route = 'common';

        $this->invoice = Invoices::find($id) ?? [];
        $this->invoiceSettings = InvoiceSettings::first();
        $this->genrealSettings = GeneralSettings::first();
        $this->organization_name = OrganizationSetting::first()->organization_name;
    }
    public function render()
    {
        $this->historys = InvEstActivity::where('invoice_id', $this->invoice->id)->orderBy('inv_est_activity_id', 'DESC')->get();

        return view('livewire.common.invoices.invoice-view');
    }

    public function updateinvoice($value)
    {
        $this->invoice->status = $value;
        $this->invoice->save();

        if ($value == 1) {
                        
            if ($this->invoice->lead_id) {
                SettingHelper::leadActivityLog(20, $this->invoice->lead_id, null);
            }
        
            SettingHelper::InvEstActivityLog(16, $this->invoice->id,  null,  null);
        } elseif ($value == 2) {
               if ($this->invoice->lead_id) {
                    SettingHelper::leadActivityLog(21, $this->invoice->lead_id, null);
                }
            SettingHelper::InvEstActivityLog(17, $this->invoice->id, null, null);
        } elseif ($value == 3) {
            if ($this->invoice->lead_id) {
                SettingHelper::leadActivityLog(22, $this->invoice->lead_id, null);
            }
            SettingHelper::InvEstActivityLog(13,$this->invoice->id,  null, null);
        }
    }

    public function seninvoice()
    {
        $encodedId = base64_encode($this->invoice->id);
        $estimatUrl = env('APP_URL') . '/invoice-portal/' . $encodedId;
        $variables = [
            '[invoice-number]'        => $this->invoice->invoice_no,
            '[organization-name]'     => $this->organization_name,
            '[client-contact-name]'   => $this->invoice->tourist->name ?? '',
            '[invoice-date]'          => $this->invoice->invoice_date,
            '[total-amount]'          => $this->invoice->amount,
            '[invoice-url]'           => $estimatUrl,
            '[po-number]'             => $this->invoice->po_number,
            '[due-date]'              => $this->invoice->expiry_date,
        ];
        SettingHelper::sendEmail(
            '2',
            $variables,
            $this->invoice->tourist->contact_email
        );
    }
}

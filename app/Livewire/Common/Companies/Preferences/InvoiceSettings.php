<?php

namespace App\Livewire\Common\Companies\Preferences;

use App\Models\Companies;
use App\Models\InvoiceSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

class InvoiceSettings extends Component
{
    public $company_id;

    public $GeneralSetting;
    public $pr_invoice_title;
    public $invoice_number, $invoice_title, $payment_terms, $column_layout, $terms_condition, $customer_note;
    public function mount($company_id = null)
    {
        $this->company_id = $company_id;

        $this->GeneralSetting = ModelsGeneralSettings::where('company_id', $this->company_id)->first();
        if ($this->GeneralSetting) {
            $this->fill($this->GeneralSetting->toArray());
        }
    }


    public function rules()
    {
        return [
            'invoice_number' => 'required',
            'invoice_title' => 'required',
            'pr_invoice_title' => 'required',
            'terms_condition' => 'required',
            'customer_note' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.common.companies.preferences.invoicesettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'invoice_number' => $this->invoice_number,
            'invoice_title' => $this->invoice_title,
            'pr_invoice_title' => $this->pr_invoice_title,
            'payment_terms' => $this->payment_terms,
            'column_layout' => $this->column_layout,
            'terms_condition' => $this->terms_condition,
            'customer_note' => $this->customer_note,
            'company_id' => $this->company_id
        ];

        if ($this->GeneralSetting) {
            $this->GeneralSetting->update($data);
        } else {
            $this->GeneralSetting = ModelsGeneralSettings::create($data);
               $Company = Companies::findOrFail($this->company_id);
           if ($Company->profile_steps < 2) {
                $Company->update([
                    "profile_steps" => 2
                ]);
            }
        }
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Saved',
            'message' => 'Invoice Settings updated successfully!'
        ]);
    }
}

<?php

namespace App\Livewire\Common\Preferences;

use App\Models\InvoiceSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class InvoiceSettings extends Component
{
    public $GeneralSetting;
    public $invoice_number, $invoice_title, $payment_terms, $column_layout, $terms_condition, $customer_note;
    public function mount()
    {
        $this->GeneralSetting = ModelsGeneralSettings::first();
        if ($this->GeneralSetting) {
            $this->fill($this->GeneralSetting->toArray());
        }
    }


    public function rules()
    {
        return [
            'invoice_number' => 'required',
            'invoice_title' => 'required',
            'terms_condition' => 'required',
            'customer_note' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.common.preferences.invoicesettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'invoice_number' => $this->invoice_number,
            'invoice_title' => $this->invoice_title,
            'payment_terms' => $this->payment_terms,
            'column_layout' => $this->column_layout,
            'terms_condition' => $this->terms_condition,
            'customer_note' => $this->customer_note
        ];

        if ($this->GeneralSetting) {
            $this->GeneralSetting->update($data);
        } else {
            $this->GeneralSetting = ModelsGeneralSettings::create($data);
        }
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Saved',
            'message' => 'Invoice Settings updated successfully!'
        ]);
    }
}

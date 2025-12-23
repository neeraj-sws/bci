<?php

namespace App\Livewire\Common\Preferences;

use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\GeneralSettings as ModelsGeneralSettings;
use Livewire\Attributes\{Layout, On};
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class GeneralSettings extends Component
{
    public $GeneralSetting;
    public $paper_size, $number_format, $notify, $notify2, $notify3, $pdf_attachment;

    public $fiscal_year, $currency, $date_format;

    public $ficalYears, $currencys;
    public $markup_rate, $usd_rate;
    


    public function mount()
    {
        $this->GeneralSetting = ModelsGeneralSettings::first();
        if ($this->GeneralSetting) {
            $this->fill($this->GeneralSetting->toArray());
            $this->pdf_attachment = (bool) $this->GeneralSetting->pdf_attachment;
            $this->notify = (bool) $this->GeneralSetting->notify;
            $this->notify2 = (bool) $this->GeneralSetting->notify2;
            $this->notify3 = (bool) $this->GeneralSetting->notify3;
        }
    }


    public function rules()
    {
        return [
            'paper_size' => 'required',
            'number_format' => 'required',
            'pdf_attachment' => 'required',
            'notify' => 'required',
            'notify2' => 'required',
            'notify3' => 'required',
            'usd_rate' => 'required|numeric',
            'markup_rate' => 'required|numeric',
        ];
    }

    public function render()
    {
        $this->ficalYears = FiscalYear::all()->pluck('name', 'id');
        $this->currencys = Currency::all()->pluck('currency', 'id');
        return view('livewire.common.preferences.generalsettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'fiscal_year' => $this->fiscal_year,
            'currency' => $this->currency,
            'date_format' => $this->date_format,
            'paper_size' => $this->paper_size,
            'number_format' => $this->number_format,
            'pdf_attachment' => $this->pdf_attachment,
            'notify' => $this->notify,
            'notify2' => $this->notify2,
            'notify3' => $this->notify3,
            'usd_rate' => $this->usd_rate,
            'markup_rate' => $this->markup_rate,
        ];

        if ($this->GeneralSetting) {
            $this->GeneralSetting->update($data);
        } else {
            $this->GeneralSetting = ModelsGeneralSettings::create($data);
        }
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Saved',
            'message' => 'General Settings updated successfully!'
        ]);
    }
    
 
}

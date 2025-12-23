<?php

namespace App\Livewire\Common\Companies\Preferences;

use App\Models\Companies;
use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\GeneralSettings as ModelsGeneralSettings;
use Livewire\Attributes\{Layout, On};
use Livewire\Component;

class GeneralSettings extends Component
{
    public $GeneralSetting;
    public $paper_size, $number_format="comma_dot", $notify, $notify2, $notify3, $pdf_attachment=1;

    public $fiscal_year, $currency, $date_format;

    public $ficalYears, $currencys;
    public $markup_rate, $usd_rate;

    public $company_id;


    public function mount($company_id = null)
    {
        $this->company_id = $company_id;

        $this->GeneralSetting = ModelsGeneralSettings::where('company_id', $this->company_id)->first();
        if ($this->GeneralSetting) {
            $this->fill($this->GeneralSetting->toArray());
            $this->pdf_attachment = (bool) 1;
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
        return view('livewire.common.companies.preferences.generalsettings');
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
            'message' => 'General Settings updated successfully!'
        ]);
    }
}

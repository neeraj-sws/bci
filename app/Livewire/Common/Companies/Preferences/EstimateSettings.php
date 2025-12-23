<?php

namespace App\Livewire\Common\Companies\Preferences;

use App\Models\Companies;
use App\Models\QuotationSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EstimateSettings extends Component
{
    public $company_id;

    public $GeneralSetting;
    public $estimate_number, $estimate_title, $terms_condition, $customer_note;
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
            'estimate_number' => ['required', function ($attribute, $value, $fail) {
                $last = substr($value, -1);
                if (!ctype_alpha($last) && !ctype_digit($last)) {
                    $fail('The estimate number must end with a letter or number.');
                }
            }],
            'estimate_title' => 'required',
            'terms_condition' => 'required',
            'customer_note' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.common.companies.preferences.estimatesettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'estimate_number' => $this->estimate_number,
            'estimate_title' => $this->estimate_title,
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
            'message' => 'Estimate Settings updated successfully!'
        ]);
    }
}

<?php

namespace App\Livewire\Common\Preferences;

use App\Models\QuotationSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class EstimateSettings extends Component
{
    public $GeneralSetting;
    public $estimate_number, $estimate_title, $terms_condition, $customer_note;
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
        return view('livewire.common.preferences.estimatesettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'estimate_number' => $this->estimate_number,
            'estimate_title' => $this->estimate_title,
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
            'message' => 'Estimate Settings updated successfully!'
        ]);
    }
}

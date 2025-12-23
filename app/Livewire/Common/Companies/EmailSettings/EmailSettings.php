<?php

namespace App\Livewire\Common\Companies\EmailSettings;

use App\Models\Companies;
use App\Models\EmailSettings as ModelsGeneralSettings;
use Livewire\Component;

class EmailSettings extends Component
{
    public $EmailSetting;
    public $type, $subject, $message;

    public $placeholders = [];
    public $company_id;



    public function mount($type = null, $company_id = null)
    {
        $this->type = $type;
        $this->company_id = $company_id;

        $this->setPlaceholdersBasedOnType($type);


        $this->EmailSetting = ModelsGeneralSettings::where('type', $type)->where('company_id', $this->company_id)->first();
        if ($this->EmailSetting) {
            $this->fill($this->EmailSetting->toArray());
        }
    }

    public function rules()
    {
        return [
            'subject' => 'required',
            'message' => 'required',
        ];
    }

    public function render()
    {
        $this->dispatch('email-settings-loaded');
        return view('livewire.common.companies.emailsettings.emailsettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => $this->type,
            'company_id' => $this->company_id,
        ];

        if ($this->EmailSetting) {
            $this->EmailSetting->update($data);
        } else {
            $this->EmailSetting = ModelsGeneralSettings::create($data);
            $Company = Companies::findOrFail($this->company_id);
            if ($Company->profile_steps < 3) {
                $Company->update([
                    "profile_steps" => 3
                ]);
            }
        }
        $this->dispatch('email-settings-loaded');

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Saved',
            'message' => 'Email Settings updated successfully!'
        ]);
    }


    protected function setPlaceholdersBasedOnType($type)
    {
        if ($type == 1) { // Invoice
            $this->placeholders = [
                'invoice-date',
                'invoice-number',
                'po-number',
                'due-date',
                'total-amount',
                'shipping-charge',
                'balance-due',
                'overdue-days',
                'invoice-title',
                'invoice-notes',
                'invoice-url',
                'project-name',
                'client-name',
                'client-contact-name',
                'organization-name',
                'user-name',
            ];
        } elseif ($type == 2) { // Estimate
            $this->placeholders = [
                'estimate-date',
                'estimate-number',
                'po-number',
                'expiry-date',
                'total-amount',
                'estimate-title',
                'estimate-notes',
                'estimate-url',
                'client-contact-name',
                'organization-name',
            ];
        } elseif ($type == 3) { // PRINVOICE
            $this->placeholders = [
                'prinvoice-date',
                'prinvoice-number',
                'expiry-date',
                'total-amount',
                'prinvoice-title',
                'prinvoice-url',
                'client-contact-name',
                'organization-name',
            ];
        }
    }
}

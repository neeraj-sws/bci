<?php

namespace App\Livewire\Common\Preferences\EmailSettings;

use App\Models\EmailSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class EmailSettings extends Component
{
    public $EmailSetting;
    public $type, $subject, $message;

    public $placeholders = [];



    public function mount($type = null)
    {
        $this->type = $type;

        $this->setPlaceholdersBasedOnType($type);


        $this->EmailSetting = ModelsGeneralSettings::where('type', $type)->first();
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
        return view('livewire.common.preferences.emailsettings.emailsettings');
    }

    public function save()
    {

        $this->validate($this->rules());

        $data = [
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => $this->type,
        ];

        if ($this->EmailSetting) {
            $this->EmailSetting->update($data);
        } else {
            $this->EmailSetting = ModelsGeneralSettings::create($data);
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
            'shipping-charge',
            'estimate-title',
            'estimate-notes',
            'estimate-url',
            'project-name',
            'client-name',
            'client-contact-name',
            'organization-name',
            'user-name',
        ];
    }
}
}

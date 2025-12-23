<?php

namespace App\Livewire\Common\Preferences\EmailSettings;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class Email extends Component
{
    public $tab=1;
    public function render()
    {
        return view('livewire.common.preferences.emailsettings.email');
    }
}

<?php

namespace App\Livewire\Common\Companies\EmailSettings;

use Livewire\Attributes\Layout;
use Livewire\Component;

class CompaniEmail extends Component
{
    public $tab=1;
    public $company_id;

    public function mount($id = null)
    {
        $this->company_id = $id;
    }
    public function render()
    {
        return view('livewire.common.companies.emailsettings.compani-email');
    }
            public function handleTabChange($tab)
    {
        $this->tab = $tab;
    }
}

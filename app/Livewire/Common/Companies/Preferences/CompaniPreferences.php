<?php

namespace App\Livewire\Common\Companies\Preferences;

use Livewire\Attributes\{Layout, On};
use Livewire\Component;

class CompaniPreferences extends Component
{
    public $company_id;
    public $tab=1;

    public function mount($id = null)
    {
        $this->company_id = $id;
    }

    public function render()
    {
        return view('livewire.common.companies.preferences.compani-preferences');
    }
        public function handleTabChange($tab)
    {
        $this->tab = $tab;
    }
}

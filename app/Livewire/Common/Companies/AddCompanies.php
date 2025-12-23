<?php

namespace App\Livewire\Common\Companies;

use App\Models\Companies;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class AddCompanies extends Component
{

    public $organization;
    public $pageTitle = 'Company';
    public $tab = 1;
    public $profileSteps = 1;
    public $isEdit;

    protected $listeners = [
        'tabUpdated' => 'handleTabChange',
        'profileSave' => 'handleSave'
    ];
    public function mount($id = null)
    {
        if ($id) {
            $this->isEdit = true;
            $this->organization = Companies::findOrFail($id);
            $this->tab = $this->organization->profile_steps === 4 ? 1 : $this->organization->profile_steps;
            $this->profileSteps = $this->organization->profile_steps;
        }
    }

    public function render()
    {
        return view('livewire.common.companies.add-companies');
    }

    public function handleTabChange($tab)
    {
        $this->tab = $tab;
    }
    public function handleSave($uuid)
    {
        $this->organization = Companies::where('company_id', $uuid)->first();
    }
}

<?php

namespace App\Livewire\Common\Leads\Comp;

use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use App\Models\LeadFollowups;
use App\Models\Leads;
class LeadsFollowup extends Component
{
    use WithPagination;

    public $followUps;
    public $leadId;

    public $view = 'livewire.common.leads.comp.leads-followup';


    public function mount($id)
    {
        $this->leadId = $id;
        $this->loadFollowups();
    }

    public function loadFollowups()
    {
        $this->followUps = LeadFollowups::where('lead_id', $this->leadId)->get();
    }

    #[On('history-status-updated')]
    public function callrendr()
    {
        $this->loadFollowups();  // Fetch new data
    }

    public function render()
    {
        return view($this->view);
    }
 
}

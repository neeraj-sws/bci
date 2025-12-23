<?php

namespace App\Livewire\Common\Leads\Comp;

use App\Models\LeadActivity;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use App\Models\Leads;
class LeadsHistory extends Component
{
    use WithPagination;

    public $historys;
    public $leadId;

    public $view = 'livewire.common.leads.comp.leads-history';


    public function mount($id)
    {
        $this->leadId = $id;
        $this->loadFollowups();
    }

    public function loadFollowups()
    {
        $this->historys = LeadActivity::where('lead_id',  $this->leadId)->get();
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

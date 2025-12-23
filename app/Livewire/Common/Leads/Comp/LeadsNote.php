<?php

namespace App\Livewire\Common\Leads\Comp;

use App\Helpers\SettingHelper;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use App\Models\LeadFollowups;
use App\Models\Leads;
use Illuminate\Support\Facades\Auth;

class LeadsNote extends Component
{
    use WithPagination;

    public $notes, $stage;
    public $leadId;
    public $coloum, $guard;

    public $view = 'livewire.common.leads.comp.leads-note';


    public function mount($id, $coloum = null, $guard = null)
    {
        $this->leadId = $id;
        $this->coloum = $coloum;
        $this->guard = $guard;
        $this->loadNotes();
    }

    public function loadNotes()
    {
        $lead = Leads::find($this->leadId);
        $this->notes = $lead->notes;
        $this->stage = $lead->stage_id;
    }
    public function render()
    {
        return view($this->view);
    }

    public function updateNotes()
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;
        $this->validate([
            'notes' => 'nullable|string',
        ]);
        $lead = Leads::find($this->leadId);
        $lead->notes = $this->notes;
        $lead->save();
        SettingHelper::leadActivityLog(4, $this->leadId, $userId, $this->coloum);
        $this->dispatch('history-status-updated');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Notes updated successfully.'
        ]);
    }
}

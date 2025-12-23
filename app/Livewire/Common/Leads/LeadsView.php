<?php

namespace App\Livewire\Common\Leads;

use App\Helpers\SettingHelper;
use App\Models\LeadFollowups;
use App\Models\Leads;
use App\Models\LeadStages;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]

class LeadsView extends Component
{
    use WithPagination;

    public $leadId;
    public $leadData;
    public $showFollowupModal = false;
    public $followup_date, $followup_time, $stage_id, $status_id, $comments, $mark;
    public $stages, $stageselects;
    public $status = [];
    public $user_id, $users;

    public $view = 'livewire.common.leads.view-leads';

    public $coloum, $route, $edit, $guard, $chehck;
    
    const SALES_ROLE_ID = 2;

    public function mount($id, $route = null, $coloum = null, $edit = true, $guard = null)
    {
        $this->route = 'common';
        $this->coloum = Auth::guard('web')->user()->hasRole('admin') ? null : 'user_id';
        $this->guard = 'web';
        $this->chehck = LeadStages::orderBy('lead_stage_id', 'desc')->first()->id;

        if ($id) {
            $this->leadId = $id;
        }
    }
    public function render()
    {
        $this->stages = LeadStages::pluck('name', 'lead_stage_id')->toArray();
        $this->status = LeadStatus::pluck('name', 'lead_status_id')->toArray();
        $this->leadData = Leads::findOrFail($this->leadId);
        // $this->users = User::role('sales')
        //             ->where('status', 1)
        //             ->pluck('name', 'user_id')
        //             ->toArray();
        
       $this->users = User::whereHas('roles', fn($q) =>
            $q->where('id', self::SALES_ROLE_ID)
        )
        ->where('status', 1)
        ->pluck('name', 'user_id')
        ->toArray();

        $this->stageselects = LeadStages::where('lead_stage_id', '>', $this->leadData->stage_id)->whereIn('lead_stage_id',[1,2,3,6])->pluck('name', 'lead_stage_id')->toArray();
        
        return view($this->view);
    }


    public function addFollowup()
    {
        $this->showFollowupModal = true;
        $this->followup_date = $this->leadData->follow_up_date;
        $this->stage_id = $this->leadData->stage_id;
        $this->status_id = $this->leadData->status_id;
    }



   public function rules()
{
    return [
        'followup_date' => 'required|date_format:Y-m-d',
        'followup_time' => 'required|date_format:H:i',
        'comments' => 'required',
    ];
}

public function messages()
{
    return [
        'followup_date.required' => 'The follow-up date is required.',
        'followup_date.date_format' => 'The follow-up date must be in Y-m-d format (e.g., 2025-10-06).',
        'followup_time.required' => 'The follow-up time is required.',
        'followup_time.date_format' => 'Follow-up time must be in HH:MM (24-hour) format, e.g., 14:30.',
        'comments.required' => 'Comments field is required.',
    ];
}


    public function storeFollowUp()
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;
        $this->validate($this->rules());

        $folloUp = LeadFollowups::create([
            'lead_id' => $this->leadId,
            'followup_date' => $this->followup_date,
            'followup_time' => $this->followup_time,
            'stage_id' => $this->stage_id,
            'status_id' => $this->status_id,
            'comments' => $this->comments,
            'mark' => $this->mark,
        ]);
        if ($this->coloum) {
            $folloUp->update([
                $this->coloum => $userId,
            ]);
        }
        
        $this->leadData->follow_up_date = $this->followup_date;
        $this->leadData->follow_up_time = $this->followup_time;
        $this->leadData->save();
       
        SettingHelper::leadActivityLog(3, $this->leadId, $userId, $this->coloum);

        $this->resetForm();
        $this->dispatch('history-status-updated');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Follow Up Added Successfully'
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'followup_date',
            'followup_time',
            'comments',
            'showFollowupModal',
            'mark'
        ]);
        $this->resetValidation();
    }


    public function updatedStageId($value)
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;

        Leads::where('lead_id', $this->leadId)->update([
            'last_stage' => $this->leadData->stage_id,
            'stage_id' => $value,
        ]);

        SettingHelper::leadActivityLog(23, $this->leadId, $userId, $this->coloum);
  
        $this->dispatch('history-status-updated');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Lead Stage Updated Successfully'
        ]);
    }

    public function updatedStatusId($value)
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;

        Leads::where('lead_id', $this->leadId)->update([
            'status_id' => $value,
        ]);
        SettingHelper::leadActivityLog(24, $this->leadId, $userId, $this->coloum);

        $this->dispatch('history-status-updated');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Lead Status Updated Successfully'
        ]);
    }

    public function userAssign()
    {
        $this->validate([
            'user_id' => 'required',
        ]);
        Leads::where('lead_id', $this->leadId)->update([
            "user_id" => $this->user_id,
        ]);
        SettingHelper::leadActivityLog(6, $this->leadId);

        $this->reset(['user_id']);
        $this->resetValidation();
        $this->dispatch('history-status-updated');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Lead Assigned Successfully'
        ]);
    }
}

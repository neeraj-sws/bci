<?php

namespace App\Livewire\Common\Leads;

use App\Models\Leads as Model;
use App\Models\LeadStages;
use App\Models\LeadStatus;
use App\Models\LeadTags;
use Illuminate\Support\Facades\Auth;
use Livewire\{Component, WithPagination};
use Livewire\Attributes\{Layout, On};
use App\Models\Tourists;
use App\Models\LeadSources;

#[Layout('components.layouts.common-app')]

class Leads extends Component
{
    use WithPagination;

    public $pageTitle = 'Leads';
    public $search = '';

    public $stages, $status, $stage_id = 1, $status_id, $tourists, $tourist_id, $sales;

    public $coloum, $route, $guard, $chehck;
    public $statusFilter = 1;
    public $sources, $source_id;

    public $startdate, $enddate;
    public $itemId;

    public $tags;

    public $searchTag = [];

    public $stageId;

    public function mount($route = null, $coloum = null, $guard = null)
    {
        $this->route = 'common';
        // NEW DEV 
        // if (Auth::guard('web')->user()->hasRole('marketing')) {
        //     $this->coloum = 'marketing_person';
        // } else {
        //     $this->coloum = Auth::guard('web')->user()->hasRole('admin') ? null : 'user_id';
        // }
        $this->guard = 'web';
        $this->stages = LeadStages::all()->pluck('name', 'id');
        $this->tourists = Tourists::select('tourist_id', 'primary_contact', 'contact_phone')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->primary_contact . ' - ' . $tourist->contact_phone];
            })
            ->toArray();
        $this->status = LeadStatus::all()->pluck('name', 'id');
        $this->chehck = LeadStages::orderBy('lead_stage_id', 'desc')->first()->id;
        $this->tags = LeadTags::where('status', 1)->pluck('name', 'lead_tag_id');

        $this->sources = LeadSources::all()->pluck('name', 'id');

        // NEW DEV 21-10-15
        $this->statusFilter = session('lead_status_filter', 1);
        $this->stage_id = session('lead_status_filter', 1);
    }

    public function setStatusFilter($status)
    {
        session(['lead_status_filter' => $status]);
        $this->statusFilter = $status;
        $this->stage_id = $status;
    }


    public function render()
    {

        $items = Model::when($this->coloum, function ($query) {
            return $query->where($this->coloum, Auth::guard($this->guard)->user()->id);
        })

            ->whereHas('tourist', function ($query) {
                $query->where('company_name', 'like', "%{$this->search}%")
                    ->orWhere('primary_contact', 'like', "%{$this->search}%");
            })
            ->when($this->stage_id, function ($query) {
                $query->where('stage_id', $this->stage_id); // Filter by stage_id
            })
            ->when($this->status_id, function ($query) {
                $query->where('status_id', $this->status_id); // Filter by status_id
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('stage_id', $this->stage_id); // Filter by status_id
            })
            ->when($this->tourist_id, function ($query) {
                $query->where('tourist_id', $this->tourist_id); // Filter by status_id
            })
            ->when($this->source_id, function ($query) {
                $query->where('source_id', $this->source_id); // Filter by status_id
            })
            ->when($this->startdate && $this->enddate, function ($query) {
                $query->whereBetween('travel_date', [$this->startdate, $this->enddate]);
            })
            ->when($this->startdate && !$this->enddate, function ($query) {
                $query->whereDate('travel_date', '>=', $this->startdate);
            })
            ->when(!$this->startdate && $this->enddate, function ($query) {
                $query->whereDate('travel_date', '<=', $this->enddate);
            })

            // NEW DEV 
            ->when($this->searchTag && count($this->searchTag) > 0, function ($query) {
                $query->where(function ($q) {
                    foreach ($this->searchTag as $tag) {
                        $q->orWhereRaw('FIND_IN_SET(?, tags)', [$tag]);
                    }
                });
            })


            ->where('soft_delete', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);


        $counts = [
            'prospect' => Model::where('stage_id', 1)->where('soft_delete', 0)->count(),
            'unqualified' => Model::where('stage_id', 2)->where('soft_delete', 0)->count(),
            'qualified' => Model::where('stage_id', 3)->where('soft_delete', 0)->count(),
            'proposal' => Model::where('stage_id', 4)->where('soft_delete', 0)->count(),
            'negotiation' => Model::where('stage_id', 5)->where('soft_delete', 0)->count(),
            'proforma' => Model::where('stage_id', 8)->where('soft_delete', 0)->count(),
            'lost' => Model::where('stage_id', 6)->where('soft_delete', 0)->count(),
            'won' => Model::where('stage_id', 7)->where('soft_delete', 0)->count(),
        ];

        return view('livewire.common.leads.leads-list', compact('items', 'counts'));
    }


    public function clearFilters()
    {
        $this->status_id = null;
        $this->tourist_id = null;
        $this->source_id = null;

        $this->startdate = null;
        $this->enddate = null;
        
        $this->searchTag = [];
        $this->dispatch('refresh');

        $this->search = '';
    }
    public function makeQualified($leadId)
    {
        $lead = Model::find($leadId);
        if ($lead) {
            $lead->stage_id = 3;
            $lead->save();
            $this->setStatusFilter(3);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => 'Lead has been marked as Qualified.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => 'Lead not found.'
            ]);
        }
    }

    // NEW DEV 
    public function confirmDelete($id)
    {
        $this->itemId = $id;

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, delete it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'delete'
        ]);
    }

    #[On('delete')]
    public function delete()
    {
        $updated = Model::findOrFail($this->itemId)->update([
            'soft_delete' => 1
        ]);
        if ($updated) {
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => $this->pageTitle . ' deleted successfully!'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to delete ' . $this->pageTitle . '. Please try again.'
            ]);
        }
    }

    // NEW DEV 21-10-25
    public function confirmStageUpdate($id, $stage = null)
    {
        $this->itemId = $id;
        $this->stageId = $stage;
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Go for now',
            'cancelButtonText' => 'Skip for now',
            'action' => 'stagedelete'
        ]);
    }
    #[On('stagedelete')]
    public function makeStageUpdate()
    {

        $lead = Model::find($this->itemId);
        if ($lead) {
            if ($this->stageId) {
                $lead->last_stage = $lead->stage_id;
                $lead->stage_id = $this->stageId;
            } else {
                $lead->stage_id = $lead->last_stage;
            }

            $lead->save();
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => 'Lead has been marked as Unqualified.'
            ]);
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => 'Lead not found.'
            ]);
        }
    }
}

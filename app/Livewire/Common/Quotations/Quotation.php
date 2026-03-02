<?php

namespace App\Livewire\Common\Quotations;

use App\Models\Companies;
use App\Models\Quotations as Model;
use App\Models\QuotationSettings;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use App\Models\Leads;

#[Layout('components.layouts.common-app')]
class Quotation extends Component
{
    use WithPagination;

    public $pageTitle;
    public $search = '';
    public $estimateSettings;

    public $statusFilter = null;
    public $startdate, $enddate;
    public $route;
    public $showModal = false, $leads;
    public $companies, $company_id;
	public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $leadSearch = '';
    public $leadSortBy = 'created_at';
    public $leadSortDirection = 'desc';
    

    public function mount()
    {
        $this->route = 'common';
        $this->estimateSettings = QuotationSettings::first();
        $this->pageTitle ='Quotations';
        $this->leads = Leads::where('stage_id', 3)->get();
        $this->companies = Companies::select('company_id', 'company_name', 'company_email')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->company_name . ' - ' . $tourist->company_email];
            })
            ->toArray();
         $this->statusFilter = session('quotation_status_filter', null);

    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
         session(['quotation_status_filter' => $status]);
        $this->statusFilter = $status;
         $this->resetPage();
    }
    
    public function updatingLeadSearch()
    {
        $this->resetPage();
    }
    
    public function sortLeadsBy($field)
    {
        if ($this->leadSortBy === $field) {
            $this->leadSortDirection = $this->leadSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->leadSortBy = $field;
            $this->leadSortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Model::query();

        if ($this->statusFilter !== null) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search !== '') {
            $query->where('quotation_no', 'like', "%{$this->search}%");
        }

        if ($this->company_id) {
            $query->where('company_id', $this->company_id);
        }


        if ($this->startdate && $this->enddate) {
            $query->whereBetween('quotation_date', [$this->startdate, $this->enddate]);
        } elseif ($this->startdate) {
            $query->whereDate('quotation_date', '>=', $this->startdate);
        } elseif ($this->enddate) {
            $query->whereDate('quotation_date', '<=', $this->enddate);
        }

        $items = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        $counts = [
            'draft' => Model::where('status', 0)->count(),
            'sent' => Model::where('status', 1)->count(),
            'accepted' => Model::where('status', 2)->count(),
            'discarded' => Model::where('status', 3)->count(),
            'revised' => Model::where('status', 4)->count(),
            'superseded' => Model::where('status', 5)->count(),
            'prinvoiced' => Model::where('status', 6)->count(),
            'invoiced' => Model::where('status', 7)->count(),
            'all' => Model::count(),
        ];
        

        $leadQuery = Leads::with('tourist', 'type')
            ->where('stage_id', 3);
    
       if (!empty($this->leadSearch) && $this->showModal) {

            $search = $this->leadSearch;
        
            $leadQuery->where(function ($q) use ($search) {
        
                $q->whereHas('tourist', function ($tourist) use ($search) {
                    $tourist->where('primary_contact', 'like', "%{$search}%");
                })
        
                ->orWhere('contact', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
            
        if ($this->leadSortBy === 'primary_contact') {

            $leadQuery->leftJoin('tourists', 'leads.tourist_id', '=', 'tourists.tourist_id')
                ->orderBy('tourists.primary_contact', $this->leadSortDirection)
                ->select('leads.*');
        
        } else {
        
            $leadQuery->orderBy($this->leadSortBy, $this->leadSortDirection);
        }
        
        $this->leads = $leadQuery->get();

        return view('livewire.common.quotations.quotation', compact('items', 'counts'));
    }

      public function add()
    {
        $this->showModal = true;
    }

        public function convertEstimate($uuid)
    {
        $this->redirect(route($this->route . '.add-quotation', ['lead_id' => $uuid]));
    }

	public function shortby($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updating()
    {
        $this->resetPage();
    }
}

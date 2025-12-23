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
        $this->statusFilter = $status;
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

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        $counts = [
            'draft' => Model::where('status', 0)->count(),
            'sent' => Model::where('status', 1)->count(),
            'prinvoiced' => Model::where('status', 6)->count(),
            'invoiced' => Model::where('status', 7)->count(),
            'all' => Model::count(),
        ];

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
}

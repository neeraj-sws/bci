<?php

namespace App\Livewire\Common\Invoices;

use App\Models\Invoices as Model;
use App\Models\InvoiceSettings;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Invoice extends Component
{
    use WithPagination;

    public $pageTitle;
    public $search = '';
    public $invoiceSettings;

    public $statusFilter = null;
    public $startdate, $enddate;

    public function mount()
    {
        $this->invoiceSettings = InvoiceSettings::first();
        $this->pageTitle = 'Invoice';
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
            $query->where('invoice_no', 'like', "%{$this->search}%");
        }


        if ($this->startdate && $this->enddate) {
            $query->whereBetween('invoice_date', [$this->startdate, $this->enddate]);
        } elseif ($this->startdate) {
            $query->whereDate('invoice_date', '>=', $this->startdate);
        } elseif ($this->enddate) {
            $query->whereDate('invoice_date', '<=', $this->enddate);
        }

        $items = $query->orderBy('updated_at', 'desc')->paginate(10);

        $counts = [
            'draft' => Model::where('status', 0)->count(),
            'sent' => Model::where('status', 1)->count(),
            'paid' => Model::where('status', 2)->count(),
            'discard' => Model::where('status', operator: 3)->count(),
            'all' => Model::count(),
        ];

        return view('livewire.common.invoices.invoice', compact('items', 'counts'));
    }
}

<?php

namespace App\Livewire\Common\TripMaster\TripView;

use App\Models\IncomeExpenses;
use App\Models\Invoices;
use App\Models\ProformaInvoices;
use App\Models\Quotations;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Records extends Component
{
    use WithPagination;
    public $pageTitle = 'Trip Master';
    public $view = 'livewire.common.trip-master.trip-view.records';

    public $invoices = [], $prformainvoices = [], $quotations = [], $incomes = [], $expenses = [], $trip_expenses = [];
    public function mount($quotationIds, $id)
    {
        $this->invoices = Invoices::whereIn('quotation_id', $quotationIds)->get();
        $this->prformainvoices = ProformaInvoices::whereIn('quotation_id', $quotationIds)->get();
        $this->quotations = Quotations::whereIn('quotation_id', $quotationIds)->get();
        $this->incomes = IncomeExpenses::where('entry_type', 2)->whereIn('quotation_id', $quotationIds)->get();
        $this->expenses = IncomeExpenses::where('entry_type', 1)->whereIn('quotation_id', $quotationIds)->get();
        $this->trip_expenses = IncomeExpenses::where('entry_type', 1)
            ->where('trip_id', $id)
            ->whereNotIn('quotation_id', $quotationIds)
            ->get();
    }
    public function render()
    {

        return view($this->view,);
    }
}

<?php

namespace App\Livewire\Common\TripMaster\TripView;

use App\Models\IncomeExpenses;
use App\Models\Quotations;
use App\Models\Trip as Model;
use App\Models\Trip;
use App\Models\TripItem;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class View extends Component
{
    use WithPagination;

    public $id;
    public $pageTitle = 'Trip Master';

    public $model = Model::class;
    public $view = 'livewire.common.trip-master.trip-view.view';

    // TOTALS 
    public $net_profit = 0, $total_income = 0, $pending_qts = 0, $total_tourist = 0, $total_expense = 0;
    public $quotationIds = [], $cashFlow = [];
    public $expenseGroupSummary = [];

    // ??TRIP DETAIL 
    public $trip;
    public function mount($id = 1)
    {
        $this->id = $id;
        $this->trip = Trip::findOrFail($id);
        $this->quotationIds = TripItem::where('trip_id', $id)->pluck('quotation_id')->toArray();

        $this->total_expense = IncomeExpenses::where('entry_type', 1)
            ->where(function ($query) use ($id) {
                $query->whereIn('quotation_id', $this->quotationIds)
                    ->orWhere('trip_id', $id);
            })
            ->sum('amount');

        $this->total_income = IncomeExpenses::where('entry_type', 2)
            ->whereIn('quotation_id', $this->quotationIds)
            ->sum('amount');


        $this->net_profit = $this->total_income - $this->total_expense;

        $this->total_tourist = Quotations::whereIn('quotation_id', $this->quotationIds)
            ->distinct('tourist_id')
            ->count('tourist_id');
        $this->cashFlow = IncomeExpenses::whereIn('quotation_id', $this->quotationIds)
            ->orWhere('trip_id', $id)
            ->get();

        $this->pending_qts = Quotations::whereIn('quotation_id', $this->quotationIds)
            ->where('total_remaning_amount', '>', 0)
            ->get();

        $this->expenseGroupSummary = IncomeExpenses::with(['category', 'subcategory'])
            ->where('entry_type', 1)
            ->where(function ($q) use ($id) {
                $q->where('trip_id', $id)
                    ->orWhereIn('quotation_id', $this->quotationIds);
            })
            ->selectRaw('category_id, sub_category_id, SUM(amount) as total_amount')
            ->groupBy('category_id', 'sub_category_id')
            ->get();
    }
    public function render()
    {
        return view($this->view,);
    }
}

<?php

namespace App\Livewire\Common\IncomeExpenses;

use App\Helpers\SettingHelper;
use App\Models\Tourists;
use App\Models\IncomeExpenseCategory;
use App\Models\IncomeExpenses as Model;
use App\Models\IncomeExpenseSubCategory;
use App\Models\Quotations;
use App\Models\Tours;
use App\Models\Vendors;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Expense extends Component
{
    use WithPagination;

    public $itemId;
    public $date, $categorys, $subcategorys = [], $category_id, $sub_category_id, $vendores, $vendor_id, $amount, $reference, $notes,
        $clients, $client_id, $tours, $tour_id, $search = '';

    public $isEditing = false;
    public $pageTitle = 'Expenses';

    public $model = Model::class;
    public $view = 'livewire.common.income-expenses.expense';


    public $quotations, $quotation_id;
    public $type;
    public $tab = 1;


    public $Filtersubcategorys = [], $catgorie_filter_id, $sub_catgorie_filter_id;


    public function rules()
    {
        $rules = [
            'amount' => 'required|numeric',
            'notes' => 'required',
            'date' => 'required',
            'type' => 'required',
            'category_id' => 'required',
        ];

        if ($this->type == 1) {
            $rules = array_merge($rules, [
                'vendor_id' => 'required',
                'reference' => 'required',
                'quotation_id' => 'required',
            ]);
        }

        return $rules;
    }


    public function render()
    {
        $query = $this->model::query();

        $query->where('entry_type', 1);

        if ($this->search) {
            $query->where(function ($q) {
                $q->orWhereHas('client', function ($clientQuery) {
                    $clientQuery->where('name', 'like', "%{$this->search}%");
                })
                    ->orWhereHas('category', function ($categoryQuery) {
                        $categoryQuery->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('tour', function ($tourQuery) {
                        $tourQuery->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('vendor', function ($tourQuery) {
                        $tourQuery->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        if ($this->catgorie_filter_id) {
            $query->where('category_id', $this->catgorie_filter_id);
        }
        if ($this->sub_catgorie_filter_id) {
            $query->where('sub_category_id', $this->sub_catgorie_filter_id);
        }
        $this->Filtersubcategorys = IncomeExpenseSubCategory::all()->pluck('name', 'id');

        $items = $query->orderBy('updated_at', 'desc')->paginate(10);
        $this->clients = Tourists::all()->pluck('primary_contact', 'id');
        $this->categorys = IncomeExpenseCategory::where('type', '1')->pluck('name', 'income_expense_category_id');
        $this->tours = Tours::all()->pluck('name', 'id');

        $this->vendores = Vendors::where('sub_type_id', $this->sub_category_id)
            ->where('status', 1)->where('soft_delete', 0)->pluck('name', 'vendor_id');

        $this->quotations = Quotations::with('tour', 'tourist')
            ->select('quotation_id', 'quotation_no', 'tour_id', 'tourist_id')
            ->whereIn('status', [2, 6, 7])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->mapWithKeys(function ($quotation) {
                return [
                    $quotation->quotation_id => $quotation->quotation_no . ' | '
                        . $quotation?->tourist?->primary_contact . ' | '  . ($quotation?->tour?->name ?? '')
                ];
            })
            ->toArray();

        return view($this->view, compact('items'));
    }
    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'date' => $this->date,
            'sub_category_id' => $this->sub_category_id,
            'category_id' => $this->category_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'tourist_id' => $this->client_id,
            'tour_id' => $this->tour_id,
            'notes' => $this->notes,
            'quotation_id' => $this->quotation_id,
        ]);
        $this->resetForm();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
    }
    public function edit($id)
    {

        $this->resetForm();
        $item = $this->model::findOrFail($id);
        $this->updatedCategoryId($item->category_id);
        if ($this->type == 1) {
            $this->client_id = $item->tourist_id;
            $this->vendor_id = $item->vendor_id;
            $this->reference = $item->reference;
            $this->tour_id = $item->tour_id;
            $this->quotation_id = $item->quotation_id;
            $this->updatedQuotationId($this->quotation_id);
        }


        $this->category_id = $item->category_id;
        $this->sub_category_id = $item->sub_category_id;

        $this->itemId = $item->id;
        $this->date = $item->date;
        $this->amount = $item->amount;
        $this->notes = $item->notes;
        $this->isEditing = true;
    }
    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'date' => $this->date,
            'sub_category_id' => $this->sub_category_id,
            'category_id' => $this->category_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'tourist_id' => $this->client_id,
            'tour_id' => $this->tour_id,
            'notes' => $this->notes,
            'quotation_id' => $this->quotation_id
        ]);

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);
    }
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
        $model = $this->model::where('income_expense_id', $this->itemId)->first();
        $model->delete();
        $this->itemId ="";
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'isEditing',
            'date',
            'category_id',
            'sub_category_id',
            'vendor_id',
            'amount',
            'reference',
            'client_id',
            'tour_id',
            'notes',
            'quotation_id',
            'type'
        ]);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $habitat = $this->model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }

    public function updatedQuotationId($id)
    {
        $qt = Quotations::where('quotation_id', $id)->first();

        if ($qt->tourist_id) {
            $this->client_id = $qt->tourist_id;
        }
        if ($qt->tour_id) {
            $this->tour_id = $qt->tour_id;
        }
        $this->reference = $qt->quotation_no . ' | ' . $qt->quotation_title;
    }
    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function exportExcel()
    {
        // build same query as render()
        $query = Model::query();
        if ($this->tab == 1) {
            $query->where('type', '1');
        } else {
            $query->where('type', '2');
        }
        $query->where('soft_delete', 0);

        if ($this->search) {
            $query->where(function ($q) {
                $q->orWhereHas('client', fn($client) => $client->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('category', fn($cat) => $cat->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('tour', fn($t) => $t->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$this->search}%"));
            });
        }



        // fetch data
        $records = $query->get();

        // prepare headings
        $headings = [
            'Date',
            'Category',
            'Vendor',
            'Amount',
            'Reference',
            'Client',
            'Tour',
            'Notes'
        ];

        // prepare rows
        $data = $records->map(function ($item) {
            return [
                $item->date,
                $item->category->name ?? '',
                $item->vendor->name ?? '',
                $item->amount,
                $item->reference,
                $item->client->primary_contact ?? '',
                $item->tour->name ?? '',
                $item->notes,
            ];
        })->toArray();

        return SettingHelper::ExportHelper('expenses', $headings, $data);
    }

    public function updatedCategoryId($id)
    {
        $this->vendor_id = null;
        $this->sub_category_id = null;
        $category = IncomeExpenseCategory::where('income_expense_category_id', $id)->first();
        if ($category) {
            if ($category->is_tour) {
                $this->type = $category->is_tour;
            } else {
                $this->type = 0;
                $this->quotation_id = 0;
                $this->reference = '';
                $this->client_id = 0;
                $this->tour_id = 0;
                $this->vendor_id = null;
            }
        }
        $this->subcategorys = IncomeExpenseSubCategory::where('category_id', $id)
            ->where('type', 1)
            ->pluck('name', 'income_expense_sub_category_id');
    }

    public function clearFilters()
    {
        $this->sub_catgorie_filter_id = '';
        $this->catgorie_filter_id = '';
    }
}

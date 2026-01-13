<?php

namespace App\Livewire\Common\IncomeExpenses;

use App\Models\Tourists;
use App\Models\IncomeExpenseCategory;
use App\Models\IncomeExpenses as Model;
use App\Models\IncomeExpenseSubCategory;
use App\Models\ProformaInvoices;
use App\Models\Quotations;
use App\Models\Tours;
use App\Models\Vendors;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Income extends Component
{
    use WithPagination;

    public $itemId;
    public $date, $categorys, $subcategorys = [], $category_id, $sub_category_id, $amount, $reference, $payment_reference, $notes,
        $clients, $client_id, $tours, $tour_id, $search = '';

    public $isEditing = false;
    public $pageTitle = 'Income';

    public $model = Model::class;
    public $view = 'livewire.common.income-expenses.income';


    public $quotations, $quotation_id;
    public $proformas = [], $proforma_invoice_id;
    public $type;
    public $tab = 1;


    public function rules()
    {
        $rules = [
            'amount' => 'required|numeric',
            'notes' => 'required',
            'date' => 'required',
            'type' => 'required',
            'payment_reference' => 'required',
            'category_id' => 'required',
        ];

        if ($this->type == 1) {
            $rules = array_merge($rules, [
                'reference' => 'required',
                'quotation_id' => 'required',
                'proforma_invoice_id' => 'required',
            ]);
        }

        return $rules;
    }


    public function render()
    {
        $query = $this->model::query();

        $query->where('entry_type', 2);


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

        $items = $query->orderBy('updated_at', 'desc')->paginate(10);
        $this->clients = Tourists::all()->pluck('primary_contact', 'id');
        $this->categorys = IncomeExpenseCategory::where('type', '2')->pluck('name', 'income_expense_category_id');
        $this->tours = Tours::all()->pluck('name', 'id');

        $this->quotations = Quotations::with('tour', 'tourist')
            ->select('quotation_id', 'quotation_no', 'tour_id', 'tourist_id')
            ->where('status', [6, 7])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->mapWithKeys(function ($quotation) {
                return [
                    $quotation->quotation_id => $quotation->quotation_no . ' | '
                        . ($quotation?->tour?->name ?? '') . ' | ' . $quotation?->tourist?->primary_contact
                ];
            })
            ->toArray();


        return view($this->view, compact('items'));
    }
    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'entry_type' => 2,
            'date' => $this->date,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'tourist_id' => $this->client_id,
            'tour_id' => $this->tour_id,
            'notes' => $this->notes,
            'quotation_id' => $this->quotation_id,
            'proforma_invoice_id' => $this->proforma_invoice_id,
            'payment_reference' => $this->payment_reference,
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
            $this->reference = $item->reference;
            $this->tour_id = $item->tour_id;
            $this->quotation_id = $item->quotation_id;
            $this->proforma_invoice_id = $item->proforma_invoice_id;
            $this->updatedQuotationId($this->quotation_id, true);
        }

        $this->category_id = $item->category_id;
        $this->sub_category_id = $item->sub_category_id;

        $this->itemId = $item->id;
        $this->date = $item->date;
        $this->amount = $item->amount;
        $this->notes = $item->notes;
        $this->payment_reference = $item->payment_reference;
        $this->isEditing = true;
    }
    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'date' => $this->date,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'tourist_id' => $this->client_id,
            'tour_id' => $this->tour_id,
            'notes' => $this->notes,
            'quotation_id' => $this->quotation_id,
            'proforma_invoice_id' => $this->proforma_invoice_id,
            'payment_reference' => $this->payment_reference,
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
            'sub_category_id',
            'category_id',
            'amount',
            'reference',
            'client_id',
            'tour_id',
            'notes',
            'quotation_id',
            'type',
            'proforma_invoice_id',
            'payment_reference'
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

    public function updatedQuotationId($id, $run = false)
    {
        $this->proformas = ProformaInvoices::where('quotation_id', $id)->pluck('proforma_invoice_no', 'proforma_invoice_id');
        if (!$run) {
            $this->client_id = '';
            $this->tour_id = '';
            $this->reference = '';
            $this->proforma_invoice_id = '';
            $this->amount = '';
        }
    }
    public function updatedProformaInvoiceId($id)
    {
        $pr = ProformaInvoices::where('proforma_invoice_id', $id)->first();

        if ($pr->tourist_id) {
            $this->client_id = $pr->tourist_id;
        }
        if ($pr->tour_id) {
            $this->tour_id = $pr->tour_id;
        }
        $this->amount = $pr->amount;
        $this->reference = $pr->proforma_invoice_no . ' | ' . $pr->proforma_invoice_title;
    }
    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function updatedCategoryId($id)
    {
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
            }
        }
        $this->subcategorys = IncomeExpenseSubCategory::where('category_id', $id)
            ->where('type', 2)
            ->pluck('name', 'income_expense_sub_category_id');
    }
}

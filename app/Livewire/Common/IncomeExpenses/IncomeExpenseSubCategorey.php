<?php

namespace App\Livewire\Common\IncomeExpenses;

use App\Models\IncomeExpenseCategory;
use App\Models\IncomeExpenseSubCategory as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class IncomeExpenseSubCategorey extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $type, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Income/Expense SubCategory';

    public $model = Model::class;
    public $view = 'livewire.common.income-expenses.income-expenses-sub-categorey';

    public $tab = 1, $searchCategories = [], $search_category_id;

    public $categorys = [], $category_id;

    public function mount()
    {
        $this->searchCategories = IncomeExpenseCategory::where('type', 1)
            ->where('status', 1)->pluck('name', 'income_expense_category_id');
    }
    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',income_expense_sub_category_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'type' => 'required',
            'category_id' => 'required'
        ];
    }


    public function render()
    {
        $query = $this->model::query();
        if ($this->tab == 1) {
            $query->where('type', 1);
        } elseif ($this->tab == 2) {
            $query->where('type', 2);
        }
        if($this->search_category_id){
           $query =  $query->where('category_id',$this->search_category_id);
        }
        $items = $query->where('name', 'like', "%{$this->search}%")
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $expensecount = $this->model::where('type', '1')->count();
        $incomecount = $this->model::where('type', '2')->count();



        return view($this->view, compact('items', 'expensecount', 'incomecount'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
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

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->category_id = $item->category_id;
        $this->type = $item->type;
        $this->status = $item->status;
        $this->isEditing = true;

        $this->updatedType($this->type, true);
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status
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
        $model = $this->model::find($this->itemId);
        $model->soft_name = $model->name;
        $model->name = null;
        $model->save();
        $model->delete();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'itemId', 'isEditing', 'status', 'type', 'category_id']);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $habitat = $this->model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
         $this->searchCategories = IncomeExpenseCategory::where('type', $tab)
            ->where('status', 1)->pluck('name', 'income_expense_category_id');
    }

    public function updatedType($id, $isClear = false)
    {
        if (!$isClear) {
            $this->category_id = '';
        }

        $this->categorys = IncomeExpenseCategory::where('type', $id)
            ->where('status', 1)->pluck('name', 'income_expense_category_id');
    }

    public function clearFilters()
    {
        $this->reset(['search_category_id', 'search']);
        $this->resetPage();
    }
}

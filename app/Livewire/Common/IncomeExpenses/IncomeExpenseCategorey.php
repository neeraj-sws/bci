<?php

namespace App\Livewire\Common\IncomeExpenses;

use App\Models\IncomeExpenseCategory as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class IncomeExpenseCategorey extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $type, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Income/Expense Category';

    public $model = Model::class;
    public $view = 'livewire.common.income-expenses.income-expenses-categorey';

    public $tab = 1;

    public $is_tour = 0;


    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',income_expense_category_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'type' => 'required',
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
            'name' => $this->name,
            'is_tour' => $this->is_tour,
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
        $this->type = $item->type;
        $this->is_tour = $item->is_tour;
        $this->status = $item->status;
        $this->isEditing = true;

        $this->dispatch('initializeIconPicker');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'is_tour' => $this->is_tour,
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
        $this->reset(['name', 'itemId', 'isEditing', 'status', 'type', 'is_tour']);
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
    }
}

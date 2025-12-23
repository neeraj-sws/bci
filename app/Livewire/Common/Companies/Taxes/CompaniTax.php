<?php

namespace App\Livewire\Common\Companies\Taxes;

use App\Models\Companies;
use App\Models\Taxes as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

class CompaniTax extends Component
{
    use WithPagination;

    public $itemId;
    public $tab;

    public $rate = 0;
    public $tax_name, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Taxes';
    public $company_id;

    public $model = Model::class;
    public $view = 'livewire.common.companies.taxes.compani-tax';

    public function mount($id = null, $tab = 1)
    {
        $this->company_id = $id;
        $this->tab = $tab;
    }

    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'tax_name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',tax_name,' . $this->itemId . ',taxe_id'
                : 'required|string|max:255|unique:' . $table . ',tax_name',
            'rate' => 'required|numeric'
        ];
    }

    public function render()
    {
        $items = $this->model::where('company_id', $this->company_id)->where('tax_name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'tax_name' => $this->tax_name,
            'rate' => $this->rate,
            'company_id' => $this->company_id,
        ]);

        $Company = Companies::findOrFail($this->company_id);
        if ($Company->profile_steps < 4) {
            $Company->update([
                "profile_steps" => 4
            ]);
        }

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
        $this->tax_name = $item->tax_name;
        $this->rate = $item->rate;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'tax_name' => $this->tax_name,
            'rate' => $this->rate,
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
        $this->model::destroy($this->itemId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['tax_name', 'itemId', 'isEditing', 'rate']);
        $this->resetValidation();
    }
}

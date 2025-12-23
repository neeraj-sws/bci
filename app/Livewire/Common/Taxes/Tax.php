<?php

namespace App\Livewire\Common\Taxes;

use App\Models\Taxes as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component,WithPagination};

#[Layout('components.layouts.common-app')]
class Tax extends Component
{
    use WithPagination;

    public $itemId;
    public $rate=0;
    public $tax_name,$search = '';
    public $isEditing = false;
    public $pageTitle = 'Taxes';

    public $model = Model::class;
    public $view = 'livewire.common.taxes.tax';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'tax_name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',tax_name,' . $this->itemId . ',taxe_id'
                : 'required|string|max:255|unique:' . $table . ',tax_name',
            'rate'=>'required|numeric'
        ];
    }

    public function render()
    {
        $items = $this->model::where('tax_name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

       return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'tax_name' => $this->tax_name,
            'rate' => $this->rate,
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
        $this->reset(['tax_name','itemId', 'isEditing','rate']);
        $this->resetValidation();
    }
}

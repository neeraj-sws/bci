<?php

namespace App\Livewire\Common\Master;

use App\Models\Parks as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Park extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Parks';

    public $model = Model::class;
    public $view = 'livewire.common.master.parks';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',park_id'
                : 'required|string|max:255|unique:' . $table . ',name',
        ];
    }


    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'name' => $this->name,
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
        $this->status = $item->status;
        $this->isEditing = true;

        $this->dispatch('initializeIconPicker');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
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
        $this->model::destroy($this->itemId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'itemId', 'isEditing', 'status']);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $habitat = $this->model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }
}

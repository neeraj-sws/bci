<?php

namespace App\Livewire\Common\Leads;

use App\Models\LeadStatus as Model;
use App\Models\LeadTypes;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Status extends Component
{
    use WithPagination;

    public $itemId;
    public $name, $pipeline_id, $pipelines, $search = '', $btn_text, $btn_bg;
    public $isEditing = false;
    public $pageTitle = 'Lead Status';

    public $model = Model::class;
    public $view = 'livewire.common.leads.leads-status';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',lead_status_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'pipeline_id' => 'required',
            'btn_bg' => 'required',
            'btn_text' => 'required',
        ];
    }

    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);
        $this->pipelines = LeadTypes::all()->pluck('name', 'id');

        return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'name' => $this->name,
            'type_id' => $this->pipeline_id,
            'btn_bg' => $this->btn_bg,
            'btn_text' => $this->btn_text,
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
        $this->pipeline_id = $item->type_id;
        $this->btn_bg = $item->btn_bg;
        $this->btn_text = $item->btn_text;
        $this->isEditing = true;

        $this->dispatch('initializeIconPicker');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
            'type_id' => $this->pipeline_id,
            'btn_bg' => $this->btn_bg,
            'btn_text' => $this->btn_text,
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
        $this->reset(['name', 'itemId', 'isEditing', 'pipeline_id', 'btn_bg', 'btn_text']);
        $this->resetValidation();
    }
}

<?php

namespace App\Livewire\Common\Tours;

use App\Models\Clients;
use App\Models\Tours as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component,WithPagination};

#[Layout('components.layouts.common-app')]
class Tour extends Component
{
    use WithPagination;

    public $itemId;
    public $status=1;
    public $name,$clients,$client_id,$start_date,$end_date,$description,$search = '';
    public $tab = 'all';

    public $isEditing = false;
    public $pageTitle = 'Tours';

    public $model = Model::class;
    public $view = 'livewire.common.tours.tour';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId
                : 'required|string|max:255|unique:' . $table . ',name',
            'client_id'=>'required',
            'description'=>'required',
        ];
    }

    public function render()
    {
       $query = $this->model::query()
        ->where(function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
              ->orWhereHas('client', function ($clientQuery) {
                  $clientQuery->where('company_name', 'like', "%{$this->search}%");
              });
        });

 $inactiveCount = $this->model::where('status', '0')->count();
    $activeCount = $this->model::where('status', '1')->count();
    $allCount = $this->model::count();

        if ($this->tab === 'active') {
            $query->where('status', '1');
        } else if ($this->tab === 'inactive') {
            $query->where('status', '0');
        }
        $items = $query->orderBy('updated_at', 'desc')->paginate(10);
        $this->clients = Clients::all()->pluck('company_name', 'id');
       return view($this->view, compact('items','inactiveCount','activeCount','allCount'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'name' => $this->name,
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
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
        $this->client_id = $item->client_id;
        $this->start_date = $item->start_date;
        $this->end_date = $item->end_date;
        $this->description = $item->description;
        $this->status = $item->status;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
            'status' => $this->status,
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
        $this->reset(['itemId', 'isEditing','name',
        'client_id',
        'start_date',
        'end_date',
        'description',
        'status']);
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

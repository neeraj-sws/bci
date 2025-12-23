<?php

namespace App\Livewire\Common\Master;

use App\Models\Parks;
use App\Models\Zones as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component,WithPagination};

#[Layout('components.layouts.common-app')]

class Zones extends Component
{
    use WithPagination;

    public $itemId;
    public $name,$nearest_city,$full_day_safari_cost,$allowed_gates,$park,  $search = '';
    public $nearest_airport,$nearest_railway,$total_cost;
    public $isEditing = false;
    public $pageTitle = 'Zones';

    public $model = Model::class;
    public $parks;
    public $view = 'livewire.common.master.zones';


  public function mount()
{
    $this->parks = Parks::where('status', 1)
        ->orderBy('park_id', 'desc')
        ->pluck('name', 'park_id')
        ->toArray();
}


public function rules()
{
    $table = (new $this->model)->getTable();
    $rule = $this->full_day_safari_cost ? 'required' : 'nullable';

    return [
        'name' => $this->isEditing ? 'required' : 'required|unique:' . $table . ',name',
        // 'park' => 'required',
        'nearest_city' => 'required|string',
        'full_day_safari_cost' => 'required',
        'allowed_gates' => $rule,
        'total_cost' => 'required_if:full_day_safari_cost,1',
    ];
}

    public function render()
    {
        $items = $this->model::with('park')->where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);
       return view($this->view, compact('items'));
    }



    public function store()
    {   
        $this->validate($this->rules());

        $this->model::create([
            'park_id' => $this->park,
            'name' => ucwords($this->name),
            'nearest_airport' => ucwords($this->nearest_airport),
            'nearest_railway' => ucwords($this->nearest_railway),
            'nearest_city' => ucwords($this->nearest_city),
            'full_day_safari_cost' => $this->full_day_safari_cost,
            'total_cost' => $this->total_cost,
            'allowed_gates' => $this->allowed_gates,
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
        $this->park = $item->park_id;
        $this->name = $item->name;
        $this->nearest_airport = $item->nearest_airport;
        $this->nearest_railway = $item->nearest_railway;
        $this->nearest_city = $item->nearest_city;
        $this->full_day_safari_cost = $item->full_day_safari_cost;
        $this->total_cost = $item->total_cost;
        $this->allowed_gates = $item->allowed_gates;
        $this->isEditing = true;

        $this->dispatch('initializeIconPicker');
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
        'park_id'               => $this->park,
        'name'                  => $this->name,
        'nearest_airport'       => $this->nearest_airport,
        'nearest_railway'       => $this->nearest_railway,
        'nearest_city'          => $this->nearest_city,
        'full_day_safari_cost'  => $this->full_day_safari_cost,
        'total_cost'            => $this->total_cost,
        'allowed_gates'         => $this->allowed_gates,
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
        $this->reset(['name','itemId', 'isEditing','park',
        'nearest_airport',
        'nearest_railway',
        'nearest_city',
        'full_day_safari_cost',
        'total_cost',
        'allowed_gates',]);
        $this->resetValidation();
    }
}

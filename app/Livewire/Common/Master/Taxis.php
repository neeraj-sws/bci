<?php

namespace App\Livewire\Common\Master;

use App\Models\City;
use App\Models\Taxis as Model;
use App\Models\Parks;
use App\Models\Zones;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Taxis extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $sedan, $crysta, $sedan_retained, $crysta_retained, $sedan_retained_two, $crysta_retained_two, $search = '';
    public $isEditing = false;
    public $isadd = false;
    public $pageTitle = 'Taxi';

    public $model = Model::class;

    public $parks, $park, $zones, $zone, $cities, $city;

    public $view = 'livewire.common.master.taxis';

    public function mount()
    {
        $this->parks = Parks::where('status', 1)
            ->orderBy('park_id', 'desc')
            ->pluck('name', 'park_id')
            ->toArray();
        $this->zones = Zones::orderBy('zone_id', 'desc')
            ->pluck('name', 'zone_id')
            ->toArray();
        $this->cities = [];
    }


    public function render()
    {
        $items = $this->model::orderBy('updated_at', 'desc')
            ->latest()->paginate(10);
        return view($this->view, compact('items'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isadd = true;
    }
    public function close()
    {
        $this->resetForm();
        $this->isadd = false;
    }



    public function store()
    {
        $this->validate([
            'city' => 'required',
            'park' => 'required',
            'zone' => 'required'
        ]);
        $this->model::create([
            'park_id' => $this->park,
            'zone_id' => $this->zone,
            'city_id' => $this->city,
            'sedan' => $this->sedan,
            'crysta' => $this->crysta,
            'sedan_retained' => $this->sedan_retained,
            'crysta_retained' => $this->crysta_retained,
            'sedan_retained_two' => $this->sedan_retained_two,
            'crysta_retained_two' => $this->crysta_retained_two,
        ]);

        $this->resetForm();
        $this->isadd = false;

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
    }

    public function edit($id)
    {

        $this->resetForm();
        $this->isadd = true;
        $item = $this->model::findOrFail($id);

        $this->itemId = $item->id;
        $this->park = $item->park_id;
        $this->zone = $item->zone_id;
        $this->city = $item->city_id;
        $this->sedan =  $item->sedan;
        $this->crysta = $item->crysta;
        $this->sedan_retained = $item->sedan_retained;
        $this->crysta_retained = $item->crysta_retained;
        $this->sedan_retained_two = $item->sedan_retained_two;
        $this->crysta_retained_two = $item->crysta_retained_two;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate([
            'city' => 'required',
            'park' => 'required',
            'zone' => 'required'
        ]);
        $this->model::findOrFail($this->itemId)->update([
            'park_id' => $this->park,
            'zone_id' => $this->zone,
            'city_id' => $this->city,
            'sedan' => $this->sedan,
            'crysta' => $this->crysta,
            'sedan_retained' => $this->sedan_retained,
            'crysta_retained' => $this->crysta_retained,
            'sedan_retained_two' => $this->sedan_retained_two,
            'crysta_retained_two' => $this->crysta_retained_two,
        ]);
        $this->resetForm();
        $this->isadd = false;
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
        $this->reset([
            'itemId',
            'isEditing',
            'park',
            'zone',
            'city',
            'sedan',
            'crysta',
            'sedan_retained',
            'crysta_retained',
            'sedan_retained_two',
            'crysta_retained_two'
        ]);
        $this->resetValidation();
    }
}

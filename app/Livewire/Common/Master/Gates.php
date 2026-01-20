<?php

namespace App\Livewire\Common\Master;

use App\Models\ParkGates as Model;
use App\Models\Parks;
use App\Models\Zones;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Gates extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $gypsy_charge, $guide_fee, $weekday_permit, $weekend_permit, $search = '';
    public $isEditing = false;
    public $isadd = false;
    public $pageTitle = 'Gates';

    public $model = Model::class;

    public $parks, $park, $zones, $zone, $gate_to_gate, $total_week_day, $total_week_end, $night_safari_permit, $drive_image;

    public $view = 'livewire.common.master.gates';

    public function mount()
    {
        $this->parks = Parks::where('status', 1)
            ->orderBy('park_id', 'desc')
            ->pluck('name', 'park_id')
            ->toArray();
        $this->zones = Zones::orderBy('zone_id', 'desc')
            ->where('park_id', $this->park)
            ->pluck('name', 'zone_id')
            ->toArray();
    }

    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',park_gate_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'zone' => 'required',
            'park' => 'required',
            'gypsy_charge' => 'required|numeric',
            'guide_fee' => 'required|numeric',
            'weekday_permit' => 'required|numeric',
            'weekend_permit' => 'required|numeric',
        ];
    }

    public function render()
    {
        $items = $this->model::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy(
                Parks::select('name')->whereColumn('parks.park_id', 'park_gates.park_id'),
                'asc'
            )
            ->paginate(10);

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
        $this->validate($this->rules());

        $this->model::create([
            'park_id' => $this->park,
            'zone_id' => $this->zone,
            'name' => ucwords($this->name),
            'gypsy_charge' => $this->gypsy_charge,
            'guide_fee' => $this->guide_fee,
            'gate_to_gate' => $this->gate_to_gate,
            'weekday_permit' => $this->weekday_permit,
            'weekend_permit' => $this->weekend_permit,
            'total_week_day' => $this->total_week_day,
            'total_week_end' => $this->total_week_end,
            'night_safari_permit' => $this->night_safari_permit,
            'drive_image' => $this->drive_image,
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

        $this->name =  $item->name;
        $this->gypsy_charge = $item->gypsy_charge;
        $this->guide_fee = $item->guide_fee;
        $this->gate_to_gate = $item->gate_to_gate;
        $this->weekday_permit = $item->weekday_permit;
        $this->weekend_permit = $item->weekend_permit;
        $this->total_week_day = $item->total_week_day;
        $this->total_week_end = $item->total_week_end;
        $this->night_safari_permit = $item->night_safari_permit;
        $this->drive_image = $item->drive_image;

        $this->zones = [];

        $this->zones = [];
        $this->updatedPark($this->park, true);

        // 2️⃣ Set the selected zone
        $this->zone = $item->zone_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'park_id' => $this->park,
            'zone_id' => $this->zone,
            'name' => $this->name,
            'gypsy_charge' => $this->gypsy_charge,
            'guide_fee' => $this->guide_fee,
            'gate_to_gate' => $this->gate_to_gate,
            'weekday_permit' => $this->weekday_permit,
            'weekend_permit' => $this->weekend_permit,
            'total_week_day' => $this->total_week_day,
            'total_week_end' => $this->total_week_end,
            'night_safari_permit' => $this->night_safari_permit,
            'drive_image' => $this->drive_image,
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
        $model = Model::find($this->itemId);
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
        $this->reset(['itemId', 'isEditing', 'status', 'name',    'gypsy_charge', 'guide_fee', 'gate_to_gate', 'weekday_permit', 'weekend_permit', 'total_week_day', 'total_week_end',    'night_safari_permit', 'drive_image', 'park', 'zone']);
        $this->resetValidation();
    }

    public function updatedPark($id, $clear = false)
    {
        if (!$clear) {
            $this->zone = '';
        }

        $this->zones = Zones::orderBy('zone_id', 'desc')
            ->where('park_id', $id)
            ->pluck('name', 'zone_id')
            ->toArray();
    }

    public function updatedWeekdayPermit()
    {
        $this->total_week_day =
            (float) ($this->gypsy_charge ?? 0) +
            (float) ($this->guide_fee ?? 0) +
            (float) ($this->weekday_permit ?? 0);
    }

    public function updatedWeekendPermit()
    {
        $this->total_week_end =
            (float) ($this->gypsy_charge ?? 0) +
            (float) ($this->guide_fee ?? 0) +
            (float) ($this->weekend_permit ?? 0);
    }


    public function updatedGuideFee()
    {
        $this->updatedWeekdayPermit();
        $this->updatedWeekendPermit();
    }
    public function updatedGypsyCharge()
    {
        $this->updatedWeekdayPermit();
        $this->updatedWeekendPermit();
    }
}

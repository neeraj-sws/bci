<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\RoomCategory as Model;
use App\Models\Hotel;
use App\Models\Occupancy;
use App\Models\RoomCategoryOccupances;

#[Layout('components.layouts.common-app')]
class RoomCategory extends Component
{
    use WithPagination;

    public $itemId;
    public $title;
    public $hotel_id;
    public $base_occupancy;
    public $max_occupancy;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Room Categories';

    public $hotels = [];

    public $ocupancy_id, $rate, $notes, $showRoomRateModal = false, $roomRateEdit = false, $roomRateIndex;
    public $roomRatesData = [],$occupances = [];
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'base_occupancy' => 'required|integer|min:1',
            'max_occupancy' => 'required|integer|gte:base_occupancy',
            'status' => 'required|in:0,1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.ocupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate'        => 'required|numeric|min:0',
        ];
    }

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->get();
        $this->occupances = Occupancy::where('status', 1)->pluck('title', 'occupancy_id')->toArray();
    }

    public function render()
    {
        $items = Model::where('title', 'like', "%{$this->search}%")
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.common.hotel-master.room-category', compact('items'));
    }

    public function store()
    {
        $this->validate();

        $roomCat = Model::create($this->payload());
        if(count($this->roomRatesData) > 0){
            foreach ($this->roomRatesData as $rate) {
                RoomCategoryOccupances::create([
                    'room_category_id' => $roomCat->id,
                    'occupancy_id' => $rate['ocupancy_id'],
                    'rate'         => $rate['rate'],
                ]);
            }
        }

        $this->resetForm();
        $this->roomRatesData = [];
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = Model::findOrFail($id);

        $this->itemId = $item->id;
        $this->title = $item->title;
        $this->hotel_id = $item->hotel_id;
        $this->base_occupancy = $item->base_occupancy;
        $this->max_occupancy = $item->max_occupancy;
        $this->status = $item->status;
        $this->roomRatesData = $item->occupancies->map(fn ($o) => [
            'ocupancy_id' => $o->occupancy_id,
            'rate'        => $o->rate,
        ])->toArray();

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        Model::findOrFail($this->itemId)->update($this->payload());

        $this->resetForm();

        $this->toast('Updated Successfully');
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
        Model::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = Model::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'base_occupancy' => $this->base_occupancy,
            'max_occupancy' => $this->max_occupancy,
            'status' => $this->status,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'hotel_id',
            'base_occupancy',
            'max_occupancy',
            'status',
            'itemId',
            'isEditing',
            'ocupancy_id', 'rate','showRoomRateModal', 'roomRateEdit','roomRatesData'
        ]);
        $this->resetValidation();
    }

    private function toast($msg)
    {
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' ' . $msg
        ]);
    }

    // NEW DEV 
        public function addRoomRates()
    {
        $this->validate([
            'ocupancy_id' => 'required|exists:occupances,occupancy_id',
            'rate' => 'required|numeric|min:0',
        ]);
        if (collect($this->roomRatesData)->contains('ocupancy_id', $this->ocupancy_id)) {
            $this->addError('ocupancy_id', 'This ocupancy is already added.');
            return;
        }

        $this->roomRatesData[] = [
            'ocupancy_id' => $this->ocupancy_id,
            'rate' => $this->rate,
            // 'night_charge' => $this->night_charge,
        ];

        $this->resetRoomRateForm();
    }

    public function editRoomRate($index)
    {
        $vehicle = $this->roomRatesData[$index];

        $this->ocupancy_id = $vehicle['ocupancy_id'];
        $this->rate = $vehicle['rate'];
        $this->roomRateIndex = $index;
        $this->roomRateEdit = true;
        $this->showRoomRateModal = true;
    }

    public function editRoomRateStore()
    {
        if (isset($this->roomRatesData[$this->roomRateIndex])) {
            $this->roomRatesData[$this->roomRateIndex] = [
                'ocupancy_id' => $this->ocupancy_id,
                'rate' => $this->rate,
            ];
        }
        $this->resetRoomRateForm();
    }

    public function removeRoomRate($index)
    {
        unset($this->roomRatesData[$index]);
        $this->roomRatesData = array_values($this->roomRatesData);
    }

    public function resetRoomRateForm()
    {
        $this->reset(['ocupancy_id', 'rate','showRoomRateModal', 'roomRateEdit']);
        $this->resetValidation();
    }

    public function showModel()
    {
        $this->showRoomRateModal = true;
    }
}

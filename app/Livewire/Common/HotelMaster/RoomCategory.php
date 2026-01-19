<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\RoomCategory as Model;
use App\Models\Hotel;
use App\Models\Occupancy;
use App\Models\RateTypes;
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

    public $hotels = [], $rateTypes = [], $rate_type;

    public $selected_occupancies = [];
    public $roomRatesData = [], $occupances = [];
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'status' => 'required|in:0,1',
            'selected_occupancies' => 'required|array|min:1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.ocupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate'        => 'required|numeric|min:0',
            'roomRatesData.*.weekend_rate' => 'required|numeric|min:0',
        ];
    }

    protected function messages()
    {
        return [
            'roomRatesData.*.rate.required' => 'Please enter rate for each occupancy.',
            'roomRatesData.*.rate.numeric'  => 'Rate must be a number.',
            'roomRatesData.*.rate.min'      => 'Rate cannot be negative.',

            'roomRatesData.*.weekend_rate.required' => 'Please enter weekend rate for each occupancy.',
            'roomRatesData.*.weekend_rate.numeric'  => 'Weekend rate must be a number.',
            'roomRatesData.*.weekend_rate.min'      => 'Weekend rate cannot be negative.',

            'roomRatesData.*.ocupancy_id.required' => 'Please select occupancy.',
            'roomRatesData.*.ocupancy_id.distinct' => 'Duplicate occupancy is not allowed.',
        ];
    }


    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->get();
        $this->occupances = Occupancy::where('status', 1)->pluck('title', 'occupancy_id')->toArray();
        $this->rateTypes = RateTypes::where('status', 1)->get();
    }

    public function render()
    {
        $items = Model::with(['rommtCategoryHotel'])->where('title', 'like', "%{$this->search}%")
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.common.hotel-master.room-category', compact('items'));
    }

    public function store()
    {
        $this->validate();

        $roomCat = Model::create($this->payload());
        if (count($this->roomRatesData) > 0) {
            foreach ($this->roomRatesData as $rate) {
                RoomCategoryOccupances::create([
                    'room_category_id' => $roomCat->id,
                    'occupancy_id' => $rate['ocupancy_id'],
                    'rate'         => $rate['rate'],
                    'weekend_rate' => $rate['weekend_rate'] ?? 0,
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
        $this->rate_type = $item->rate_type_id;
        $this->roomRatesData = $item->occupancies->map(fn($o) => [
            'ocupancy_id' => $o->occupancy_id,
            'rate'        => $o->rate,
            'weekend_rate' => $o->weekend_rate ?? 0,
        ])->toArray();
        $this->selected_occupancies = $item->occupancies->pluck('occupancy_id')->toArray();

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $roomCat = Model::findOrFail($this->itemId);
        $roomCat->update($this->payload());

        // Delete existing occupancies and recreate
        RoomCategoryOccupances::where('room_category_id', $roomCat->id)->delete();

        if (count($this->roomRatesData) > 0) {
            foreach ($this->roomRatesData as $rate) {
                RoomCategoryOccupances::create([
                    'room_category_id' => $roomCat->id,
                    'occupancy_id' => $rate['ocupancy_id'],
                    'rate'         => $rate['rate'],
                    'weekend_rate' => $rate['weekend_rate'] ?? 0,
                ]);
            }
        }

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
            'rate_type_id' => $this->rate_type,
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
            'roomRatesData',
            'rate_type',
            'selected_occupancies',
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

    public function updatedSelectedOccupancies()
    {

        $currentOccupancies = collect($this->roomRatesData)->pluck('ocupancy_id')->toArray();

        foreach ($this->selected_occupancies as $occupancyId) {
            if (!in_array($occupancyId, $currentOccupancies)) {
                $this->roomRatesData[] = [
                    'ocupancy_id' => $occupancyId,
                    'rate' => 0,
                    'weekend_rate' => 0,
                ];
            }
        }
        $this->roomRatesData = array_values(array_filter($this->roomRatesData, function ($item) {
            return in_array($item['ocupancy_id'], $this->selected_occupancies);
        }));
    }
}

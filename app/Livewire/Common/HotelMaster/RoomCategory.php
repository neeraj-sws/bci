<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\RoomCategory as Model;
use App\Models\Hotel;
use App\Models\RateTypes;

#[Layout('components.layouts.hotel-app')]
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
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    public $hotels = [], $rateTypes = [], $rate_type;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'status' => 'required|in:0,1',
        ];
    }


    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->get();
        $this->rateTypes = RateTypes::where('status', 1)->get();
    }

    public function render()
    {
        $items = Model::with(['rommtCategoryHotel'])->where('title', 'like', "%{$this->search}%")
           ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.common.hotel-master.room-category', compact('items'));
    }

    public function store()
    {
        $this->validate();

        $roomCat = Model::create($this->payload());

        $this->resetForm();
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

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $roomCat = Model::findOrFail($this->itemId);
        $roomCat->update($this->payload());

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
            'rate_type',
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

    public function sortby($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }
}

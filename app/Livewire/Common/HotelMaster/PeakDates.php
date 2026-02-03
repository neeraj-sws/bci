<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\PeackDate;
use App\Models\Hotel;
use App\Models\RoomCategory;

#[Layout('components.layouts.hotel-app')]
class PeakDates extends Component
{
    use WithPagination;

    public $itemId;
    public $title;
    public $hotel_id;
    public $is_new_year = 0;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Peak Dates';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    public $hotels = [];
    public $roomCategoys = [];
    public $selected_room_categories = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'is_new_year' => 'nullable|boolean',
            'status' => 'required|in:0,1',
            'selected_room_categories' => 'required|array|min:1',
            'selected_room_categories.*' => 'exists:room_categoris,room_categoris_id',
        ];
    }

    protected $validationAttributes = [
        'title' => 'Peak Date Title',
        'hotel_id' => 'Hotel',
        'is_new_year' => 'New Year Peak',
        'selected_room_categories' => 'Room Categories',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->orderBy('name')->get();
    }

    public function render()
    {
        $items = PeackDate::with('hotel')
            ->where('title', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.common.hotel-master.peak-dates', compact('items'));
    }

    public function updatedHotelId($id)
    {
        $this->roomCategoys = RoomCategory::where('hotel_id', $id)->where('status', 1)->pluck('title', 'room_categoris_id')->toArray();
        $this->selected_room_categories = [];
    }

    public function store()
    {
        $this->validate();

        foreach ($this->selected_room_categories as $room_category_id) {
            PeackDate::create([
                'title' => ucwords($this->title),
                'hotel_id' => $this->hotel_id,
                'is_new_year' => $this->is_new_year ? 1 : 0,
                'status' => $this->status,
                'room_category_id' => $room_category_id,
            ]);
        }

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = PeackDate::findOrFail($id);

        $this->itemId = $item->id;
        $this->title = $item->title;
        $this->hotel_id = $item->hotel_id;
        $this->is_new_year = $item->is_new_year;
        $this->status = $item->status;
        $this->selected_room_categories = [$item->room_category_id];

        $this->roomCategoys = RoomCategory::where('hotel_id', $this->hotel_id)
            ->where('status', 1)
            ->pluck('title', 'room_categoris_id')
            ->toArray();

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $peakDate = PeackDate::findOrFail($this->itemId);
        $peakDate->update([
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'is_new_year' => $this->is_new_year ? 1 : 0,
            'status' => $this->status,
            'room_category_id' => $this->selected_room_categories[0] ?? $peakDate->room_category_id,
        ]);

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
        PeackDate::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = PeackDate::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'title',
            'hotel_id',
            'is_new_year',
            'status',
            'isEditing',
            'selected_room_categories',
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

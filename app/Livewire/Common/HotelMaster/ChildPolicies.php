<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};

use App\Models\ChildPolicy;
use App\Models\Hotel;
use App\Models\PeackDate;
use App\Models\RoomCategory;

#[Layout('components.layouts.common-app')]
class ChildPolicies extends Component
{
    use WithPagination;

    public $itemId;
    public $hotel_id;
    public $free_child_age;
    public $child_with_bed_rate;
    public $child_without_bed_rate;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Extra Bed Child Policies';

    public $hotels = [];
    public $roomCategoys = [], $room_category_id, $peakDates = [], $is_peak_date = false, $peak_date_id;

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'room_category_id' => 'required|exists:room_categoris,room_categoris_id',
            'free_child_age' => 'required',
            'child_with_bed_rate' => 'required|numeric|min:0',
            // 'child_without_bed_rate' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'peak_date_id' => 'required_if:is_peak_date,true',
        ];
    }

    protected $validationAttributes = [
        'hotel_id' => 'Hotel',
        'free_child_age' => 'Free Child Age',
        'child_with_bed_rate' => 'Child With Bed Rate',
        'child_without_bed_rate' => 'Child Without Bed Rate',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        $items = ChildPolicy::with('hotel')
            ->whereHas('hotel', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.common.hotel-master.child-policies', compact('items'));
    }

    public function updatedHotelId($id)
    {
        $this->roomCategoys = RoomCategory::where('hotel_id', $id)->where('status', 1)->pluck('title', 'room_categoris_id')->toArray();
    }

    public function store()
    {
        $this->validate();

        ChildPolicy::create($this->payload());

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = ChildPolicy::findOrFail($id);

        $this->itemId = $item->id;
        $this->hotel_id = $item->hotel_id;
        $this->free_child_age = $item->free_child_age;
        $this->child_with_bed_rate = $item->child_with_bed_rate;
        $this->child_without_bed_rate = $item->child_without_bed_rate;
        $this->status = $item->status;
        $this->peak_date_id = $item->peak_date_id;
        if ($item->peak_date_id) {
            $this->is_peak_date = true;
        } else {
            $this->is_peak_date = false;
        }
        $this->room_category_id = $item->room_category_id;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        ChildPolicy::findOrFail($this->itemId)->update($this->payload());

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
        ChildPolicy::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = ChildPolicy::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'hotel_id' => $this->hotel_id,
            'free_child_age' => $this->free_child_age,
            'child_with_bed_rate' => $this->child_with_bed_rate,
            'child_without_bed_rate' => $this->child_without_bed_rate,
            'status' => $this->status,
            'room_category_id' => $this->room_category_id,
            'peak_date_id' => $this->peak_date_id,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'hotel_id',
            'free_child_age',
            'child_with_bed_rate',
            'child_without_bed_rate',
            'status',
            'isEditing',
            'peak_date_id',
            'room_category_id',
            'is_peak_date'
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
    public function updatedIsPeakDate($value)
    {
        if (!$value) {
            $this->peak_date_id = null;
            $this->peakDates = [];
        }
    }
    public function updatedRoomCategoryId($id)
    {
        $this->peakDates = PeackDate::where('hotel_id', $this->hotel_id)->where('room_category_id', $id)->where('status', 1)->pluck('title', 'peak_dates_id')->toArray();
    }
}

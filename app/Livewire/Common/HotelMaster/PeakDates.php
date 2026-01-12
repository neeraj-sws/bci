<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\PeackDate;
use App\Models\Hotel;
use App\Models\Occupancy;
use App\Models\PeakDateRoomCategoryOccupances;
use App\Models\RoomCategory;

#[Layout('components.layouts.common-app')]
class PeakDates extends Component
{
    use WithPagination;

    public $itemId;
    public $title;
    public $hotel_id;
    public $start_date;
    public $end_date;
    public $is_new_year = 0;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Peak Dates';

    public $hotels = [];
    public $show_notes = false, $notes;
    public $ocupancy_id, $rate,$showRoomRateModal = false, $roomRateEdit = false, $roomRateIndex,$roomCategoys = [],$room_category_id;
    public $roomRatesData = [],$occupances = [];
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_new_year' => 'nullable|boolean',
            'status' => 'required|in:0,1',
            'notes' => $this->show_notes ? 'required|string|min:5' : 'nullable',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.ocupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate'        => 'required|numeric|min:0',
            'room_category_id' => 'required|exists:room_categoris,room_categoris_id',
        ];
    }

    protected $validationAttributes = [
        'title' => 'Peak Date Title',
        'hotel_id' => 'Hotel',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'is_new_year' => 'New Year Peak',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->orderBy('name')->get();
        $this->occupances = Occupancy::where('status', 1)->pluck('title', 'occupancy_id')->toArray();
        $this->roomCategoys = RoomCategory::where('status',1)->pluck('title','room_categoris_id')->toArray();
    }

    public function render()
    {
        $items = PeackDate::with('hotel')
            ->where('title', 'like', "%{$this->search}%")
            ->orderBy('start_date')
            ->paginate(10);

        return view('livewire.common.hotel-master.peak-dates', compact('items'));
    }

    public function store()
    {
        $this->validate();

        $peakdate = PeackDate::create($this->payload());
        if(count($this->roomRatesData) > 0){
            foreach ($this->roomRatesData as $rate) {
                PeakDateRoomCategoryOccupances::create([
                    'peak_date_id' => $peakdate->id,
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
        $item = PeackDate::findOrFail($id);

        $this->itemId = $item->id;
        $this->title = $item->title;
        $this->hotel_id = $item->hotel_id;
        $this->start_date = $item->start_date;
        $this->end_date = $item->end_date;
        $this->is_new_year = $item->is_new_year;
        $this->status = $item->status;
        $this->roomRatesData = $item->occupancies->map(fn ($o) => [
            'ocupancy_id' => $o->occupancy_id,
            'rate'        => $o->rate,
        ])->toArray();
        $this->notes = $item->notes;
        if($this->notes){
            $this->show_notes = true;
        }
        $this->room_category_id = $item->room_category_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        PeackDate::findOrFail($this->itemId)->update($this->payload());

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

    private function payload(): array
    {
        return [
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_new_year' => $this->is_new_year ? 1 : 0,
            'status' => $this->status,
            'notes' => $this->show_notes ? $this->notes : null,
            'room_category_id' => $this->room_category_id,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'title',
            'hotel_id',
            'start_date',
            'end_date',
            'is_new_year',
            'status',
            'isEditing',
            'room_category_id',
            'ocupancy_id', 'rate','showRoomRateModal', 'roomRateEdit','roomRatesData','notes','show_notes'
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
    public function updatedShowNotes($value)
{
    if (!$value) {
        $this->notes = null;
        $this->resetValidation('notes');
    }
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

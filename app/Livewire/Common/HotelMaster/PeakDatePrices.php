<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\PeackDate;
use App\Models\Season;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\PeakDateRoomCategoryOccupances;

#[Layout('components.layouts.hotel-app')]
class PeakDatePrices extends Component
{
    use WithPagination;

    public $itemId;
    public $peak_date_id;
    public $season_id;
    public $start_date;
    public $end_date;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Peak Date Prices';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    // Filter properties
    public $filter_peak_date_id;
    public $filter_season_id;
    public $filter_room_category_id;
    public $filter_hotel_id;

    public $peakDates = [];
    public $seasons = [];
    public $hotels = [];
    public $roomCategories = [];
    public $occupancies = [];
    public $roomRatesData = [];
    public $selected_occupancies = [];

    protected function rules()
    {
        return [
            'peak_date_id' => 'required|exists:peak_dates,peak_dates_id',
            'season_id' => 'required|exists:seasons,seasons_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'selected_occupancies' => 'required|array|min:1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.occupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate' => 'required|numeric|min:0',
            'roomRatesData.*.weekend_rate' => 'required|numeric|min:0',
        ];
    }

    protected function messages()
    {
        return [
            'roomRatesData.*.rate.required' => 'Please enter rate for each occupancy.',
            'roomRatesData.*.rate.numeric' => 'Rate must be a number.',
            'roomRatesData.*.rate.min' => 'Rate cannot be negative.',

            'roomRatesData.*.weekend_rate.required' => 'Please enter weekend rate for each occupancy.',
            'roomRatesData.*.weekend_rate.numeric' => 'Weekend rate must be a number.',
            'roomRatesData.*.weekend_rate.min' => 'Weekend rate cannot be negative.',

            'roomRatesData.*.occupancy_id.required' => 'Please select occupancy.',
            'roomRatesData.*.occupancy_id.distinct' => 'Duplicate occupancy is not allowed.',
        ];
    }

    protected $validationAttributes = [
        'peak_date_id' => 'Peak Date',
        'season_id' => 'Season',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
    ];

    public function mount()
    {
        $this->loadDropdowns();
    }

    private function loadDropdowns()
    {
        $this->hotels = Hotel::where('status', 1)->orderBy('name')->get();
        $this->peakDates = PeackDate::where('status', 1)->orderBy('title')->get();
        $this->seasons = Season::where('status', 1)->orderBy('name')->get();
    }

    public function render()
    {
        $query = PeakDateRoomCategoryOccupances::with([
            'peakDate.hotel',
            'peakDate.roomCategory',
            'occupancy',
            'season'
        ]);

        // Apply filters
        if ($this->filter_peak_date_id) {
            $query->where('peak_date_id', $this->filter_peak_date_id);
        }

        if ($this->filter_season_id) {
            $query->where('season_id', $this->filter_season_id);
        }

        if ($this->filter_room_category_id) {
            $query->whereHas('peakDate', function ($q) {
                $q->where('room_category_id', $this->filter_room_category_id);
            });
        }

        if ($this->filter_hotel_id) {
            $query->whereHas('peakDate', function ($q) {
                $q->where('hotel_id', $this->filter_hotel_id);
            });
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('peakDate', function ($subQ) {
                    $subQ->where('title', 'like', "%{$this->search}%");
                })
                    ->orWhereHas('season', function ($subQ) {
                        $subQ->where('title', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('occupancy', function ($subQ) {
                        $subQ->where('title', 'like', "%{$this->search}%");
                    });
            });
        }

        $items = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        return view('livewire.common.hotel-master.peak-date-prices', compact('items'));
    }

    public function updatedFilterHotelId($hotelId)
    {
        if ($hotelId) {
            $this->roomCategories = RoomCategory::where('hotel_id', $hotelId)
                ->where('status', 1)
                ->orderBy('title')
                ->get();
        } else {
            $this->roomCategories = [];
        }
        $this->filter_room_category_id = null;
    }

    public function updatedPeakDateId($peakDateId)
    {
        if ($peakDateId) {
            $peakDate = PeackDate::with('roomCategory.occupancies.occupancy')->find($peakDateId);

            if ($peakDate && $peakDate->roomCategory && $peakDate->roomCategory->occupancies->count() > 0) {
                $this->occupancies = $peakDate->roomCategory->occupancies
                    ->pluck('occupancy.title', 'occupancy.occupancy_id')
                    ->toArray();
            } else {
                $this->occupancies = [];
            }

            $this->selected_occupancies = [];
            $this->roomRatesData = [];
        } else {
            $this->occupancies = [];
            $this->roomRatesData = [];
        }
    }

    public function store()
    {
        $this->validate();

        // Create price records for each occupancy
        foreach ($this->roomRatesData as $rateData) {
            PeakDateRoomCategoryOccupances::create([
                'peak_date_id' => $this->peak_date_id,
                'season_id' => $this->season_id,
                'occupancy_id' => $rateData['occupancy_id'],
                'rate' => $rateData['rate'],
                'weekend_rate' => $rateData['weekend_rate'],
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
        }

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = PeakDateRoomCategoryOccupances::with('peakDate.roomCategory.occupancies.occupancy')->findOrFail($id);

        $this->itemId = $item->id;
        $this->peak_date_id = $item->peak_date_id;
        $this->season_id = $item->season_id;
        $this->start_date = $item->start_date;
        $this->end_date = $item->end_date;
        $this->status = 1;

        // Load occupancies for the selected peak date
        if ($item->peakDate && $item->peakDate->roomCategory && $item->peakDate->roomCategory->occupancies->count() > 0) {
            $this->occupancies = $item->peakDate->roomCategory->occupancies
                ->pluck('occupancy.title', 'occupancy.occupancy_id')
                ->toArray();
        }

        // Load existing rates for this peak date + season combination
        $existingRates = PeakDateRoomCategoryOccupances::where('peak_date_id', $this->peak_date_id)
            ->where('season_id', $this->season_id)
            ->where('start_date', $this->start_date)
            ->where('end_date', $this->end_date)
            ->get()
            ->keyBy('occupancy_id');

        $this->roomRatesData = [];
        $this->selected_occupancies = [];

        foreach ($this->occupancies as $occupancyId => $occupancyName) {
            if (isset($existingRates[$occupancyId])) {
                $this->selected_occupancies[] = $occupancyId;
                $this->roomRatesData[] = [
                    'id' => $existingRates[$occupancyId]->id,
                    'occupancy_id' => $occupancyId,
                    'rate' => $existingRates[$occupancyId]->rate,
                    'weekend_rate' => $existingRates[$occupancyId]->weekend_rate,
                ];
            }
        }

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        // Delete existing rates for this peak date + season + date range combination
        PeakDateRoomCategoryOccupances::where('peak_date_id', $this->peak_date_id)
            ->where('season_id', $this->season_id)
            ->where('start_date', $this->start_date)
            ->where('end_date', $this->end_date)
            ->delete();

        // Re-create all rates
        foreach ($this->roomRatesData as $rateData) {
            PeakDateRoomCategoryOccupances::create([
                'peak_date_id' => $this->peak_date_id,
                'season_id' => $this->season_id,
                'occupancy_id' => $rateData['occupancy_id'],
                'rate' => $rateData['rate'],
                'weekend_rate' => $rateData['weekend_rate'],
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
        }

        $this->resetForm();
        $this->toast('Updated Successfully');
    }

    public function confirmDelete($id)
    {
        $this->itemId = $id;

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This will delete all occupancy rates for this date range.',
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
        // Find the record to get its details
        $item = PeakDateRoomCategoryOccupances::findOrFail($this->itemId);

        // Delete all related occupancy rates for this peak date + season + date range
        PeakDateRoomCategoryOccupances::where('peak_date_id', $item->peak_date_id)
            ->where('season_id', $item->season_id)
            ->where('start_date', $item->start_date)
            ->where('end_date', $item->end_date)
            ->delete();

        $this->toast('Deleted Successfully');
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'peak_date_id',
            'season_id',
            'start_date',
            'end_date',
            'status',
            'isEditing',
            'occupancies',
            'roomRatesData',
            'selected_occupancies',
        ]);
        $this->resetValidation();
    }

    public function clearFilters()
    {
        $this->reset([
            'filter_peak_date_id',
            'filter_season_id',
            'filter_room_category_id',
            'filter_hotel_id',
            'search'
        ]);
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
        $currentOccupancies = collect($this->roomRatesData)->pluck('occupancy_id')->toArray();

        // Add new selections
        foreach ($this->selected_occupancies as $occupancyId) {
            if (!in_array($occupancyId, $currentOccupancies)) {
                $this->roomRatesData[] = [
                    'occupancy_id' => $occupancyId,
                    'rate' => 0,
                    'weekend_rate' => 0,
                ];
            }
        }

        // Remove deselected items
        $this->roomRatesData = array_values(array_filter($this->roomRatesData, function ($item) {
            return in_array($item['occupancy_id'], $this->selected_occupancies);
        }));
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

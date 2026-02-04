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
use App\Models\RoomCategoryOccupances;

#[Layout('components.layouts.hotel-app')]
class PeakDatePrices extends Component
{
    use WithPagination;

    public $itemId;
    public $peak_date_id;
    public $start_date;
    public $end_date;
    public $status = 1;
    public $hotel_id;
    public $title;
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
    public $selected_room_categories = [];

    public $highestEndDate, $lowestStartDate;

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'selected_room_categories' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:0,1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.occupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate' => 'required|numeric|min:0',
            'roomRatesData.*.weekend_rate' => 'required|numeric|min:0',
        ];

        // Only validate peak_date_id when editing
        if ($this->isEditing && !$this->peak_date_id) {
            $rules['peak_date_id'] = 'required|exists:peak_dates,peak_dates_id';
        }

        return $rules;
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

    /**
     * Auto-detect season based on start_date and end_date
     * Returns season_id if found, null otherwise
     * Falls back to nearest season if exact match not found
     */
    private function detectSeason()
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        // Find season that fully contains the date range
        $season = Season::where('status', 1)
            ->whereDate('start_date', '<=', $this->start_date)
            ->whereDate('end_date', '>=', $this->end_date)
            ->first();

        if ($season) {
            return $season->seasons_id;
        }

        // Fallback: Find nearest season based on start_date
        $season = Season::where('status', 1)
            ->orderBy('start_date', 'desc')
            ->where('start_date', '<=', $this->start_date)
            ->first();

        return $season ? $season->seasons_id : null;
    }

    /**
     * Get season based on selected date range or fallback to current/nearest season
     * Priority:
     * 1. Season matching the selected date range
     * 2. Nearest available season
     */
    private function resolveSeasonForDateRange()
    {
        // If dates are set, detect season from them
        if ($this->start_date && $this->end_date) {
            // Find season that fully contains the date range
            $season = Season::where('status', 1)
                ->whereDate('start_date', '<=', $this->start_date)
                ->whereDate('end_date', '>=', $this->end_date)
                ->first();

            if ($season) {
                return $season->seasons_id;
            }

            // Fallback: Find nearest season based on start_date
            $season = Season::where('status', 1)
                ->orderBy('start_date', 'desc')
                ->where('start_date', '<=', $this->start_date)
                ->first();

            if ($season) {
                return $season->seasons_id;
            }

            // Last resort: Get any future season
            $season = Season::where('status', 1)
                ->orderBy('start_date', 'asc')
                ->where('start_date', '>', $this->start_date)
                ->first();

            return $season ? $season->seasons_id : null;
        }

        // If no dates set, try current season
        $currentDate = now();
        $currentSeason = Season::where('status', 1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->first();

        if ($currentSeason) {
            return $currentSeason->seasons_id;
        }

        // Fallback: Get nearest future season
        $season = Season::where('status', 1)
            ->orderBy('start_date', 'asc')
            ->where('start_date', '>', $currentDate)
            ->first();

        if ($season) {
            return $season->seasons_id;
        }

        // Last fallback: Get any season (most recent)
        $season = Season::where('status', 1)
            ->orderBy('start_date', 'desc')
            ->first();

        return $season ? $season->seasons_id : null;
    }

    public function render()
    {
        $query = PeakDateRoomCategoryOccupances::with([
            'peakDate.hotel',
            'peakDate.roomCategory',
            'peakDate.season',
            'occupancy',
        ]);

        // Apply filters
        if ($this->filter_peak_date_id) {
            $query->where('peak_date_id', $this->filter_peak_date_id);
        }

        if ($this->filter_season_id) {
            $query->whereHas('peakDate.season', function ($q) {
                $q->where('seasons_id', $this->filter_season_id);
            });
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
                    ->orWhereHas('peakDate.season', function ($subQ) {
                        $subQ->where('name', 'like', "%{$this->search}%");
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
                ->pluck('title', 'room_categoris_id')
                ->toArray();
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

            $this->roomRatesData = [];
        } else {
            $this->occupancies = [];
            $this->roomRatesData = [];
        }
    }

    public function store()
    {
        $this->validate();

        // Auto-detect season with fallback
        $season_id = $this->detectSeason();
        if (!$season_id) {
            $this->addError('start_date', 'No season available. Please create a season first.');
            return;
        }

        $roomCategoryId = is_array($this->selected_room_categories)
            ? (count($this->selected_room_categories) > 0 ? $this->selected_room_categories[0] : null)
            : $this->selected_room_categories;

        // Save in peak_dates table with season_id, start_date, end_date
        $peak_date  = PeackDate::create([
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'status' => $this->status,
            'room_category_id' => $roomCategoryId,
            'season_id' => $season_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        $this->peak_date_id = $peak_date->id;

        // Save occupancy rates (no longer storing season_id, start_date, end_date here)
        foreach ($this->roomRatesData as $rateData) {
            PeakDateRoomCategoryOccupances::create([
                'peak_date_id' => $this->peak_date_id,
                'occupancy_id' => $rateData['occupancy_id'],
                'rate' => $rateData['rate'],
                'weekend_rate' => $rateData['weekend_rate'],
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

        // Load season_id, start_date, end_date from peak_dates table
        $this->start_date = $item->peakDate->start_date;
        $this->end_date = $item->peakDate->end_date;
        $this->status = $item->peakDate->status ?? 1;

        $this->title = $item->peakDate->title;
        $this->hotel_id = $item->peakDate->hotel_id;

        $this->selected_room_categories = $item->peakDate->room_category_id ? [$item->peakDate->room_category_id] : [];

        $this->roomCategories = RoomCategory::where('hotel_id', $this->hotel_id)
            ->where('status', 1)
            ->pluck('title', 'room_categoris_id')
            ->toArray();

        if ($item->peakDate && $item->peakDate->roomCategory && $item->peakDate->roomCategory->occupancies->count() > 0) {
            $this->occupancies = $item->peakDate->roomCategory->occupancies
                ->pluck('occupancy.title', 'occupancy.occupancy_id')
                ->toArray();
        }

        // Load existing rates (no longer filtering by season_id, start_date, end_date)
        $existingRates = PeakDateRoomCategoryOccupances::where('peak_date_id', $this->peak_date_id)
            ->get()
            ->keyBy('occupancy_id');

        $this->roomRatesData = [];

        foreach ($this->occupancies as $occupancyId => $occupancyName) {
            if (isset($existingRates[$occupancyId])) {
                $this->roomRatesData[] = [
                    'id' => $existingRates[$occupancyId]->id,
                    'occupancy_id' => $occupancyId,
                    'rate' => $existingRates[$occupancyId]->rate,
                    'weekend_rate' => $existingRates[$occupancyId]->weekend_rate,
                ];
            }
        }

        // Trigger date range calculation
        $this->updatedSelectedRoomCategories($this->selected_room_categories);

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        // Auto-detect season with fallback
        $season_id = $this->detectSeason();
        if (!$season_id) {
            $this->addError('start_date', 'No season available. Please create a season first.');
            return;
        }

        $roomCategoryId = is_array($this->selected_room_categories)
            ? (count($this->selected_room_categories) > 0 ? $this->selected_room_categories[0] : null)
            : $this->selected_room_categories;

        // Update peak_dates table with season_id, start_date, end_date
        $peakDate = PeackDate::findOrFail($this->peak_date_id);
        $peakDate->update([
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'status' => $this->status,
            'room_category_id' => $roomCategoryId,
            'season_id' => $season_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        // Delete old rates and create new ones (no longer storing season_id, start_date, end_date)
        PeakDateRoomCategoryOccupances::where('peak_date_id', $this->peak_date_id)
            ->delete();

        foreach ($this->roomRatesData as $rateData) {
            PeakDateRoomCategoryOccupances::create([
                'peak_date_id' => $this->peak_date_id,
                'occupancy_id' => $rateData['occupancy_id'],
                'rate' => $rateData['rate'],
                'weekend_rate' => $rateData['weekend_rate'],
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
        $item = PeakDateRoomCategoryOccupances::findOrFail($this->itemId);

        // Delete all rates for this peak_date
        PeakDateRoomCategoryOccupances::where('peak_date_id', $item->peak_date_id)
            ->delete();

        // Delete the peak_date itself
        PeackDate::where('peak_dates_id', $item->peak_date_id)->delete();

        $this->toast('Deleted Successfully');
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'peak_date_id',
            'start_date',
            'end_date',
            'status',
            'title',
            'hotel_id',
            'selected_room_categories',
            'isEditing',
            'occupancies',
            'roomRatesData',
            'lowestStartDate',
            'highestEndDate',
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

    public function sortby($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedHotelId($id)
    {
        $this->roomCategories = RoomCategory::where('hotel_id', $id)->where('status', 1)->pluck('title', 'room_categoris_id')->toArray();
        $this->selected_room_categories = [];
        $this->occupancies = [];
        $this->roomRatesData = [];
        $this->lowestStartDate = null;
        $this->highestEndDate = null;
    }

    /**
     * Watch start_date and reload rates if room category is selected
     */
    public function updatedStartDate($value)
    {
        if ($this->selected_room_categories) {
            $this->updatedSelectedRoomCategories($this->selected_room_categories);
        }
    }

    /**
     * Watch end_date and reload rates if room category is selected
     */
    public function updatedEndDate($value)
    {
        if ($this->selected_room_categories) {
            $this->updatedSelectedRoomCategories($this->selected_room_categories);
        }
    }

    public function updatedSelectedRoomCategories($roomCategoryId)
    {
        $this->occupancies   = [];
        $this->roomRatesData = [];

        if (is_array($roomCategoryId)) {
            $roomCategoryId = count($roomCategoryId) > 0 ? $roomCategoryId[0] : null;
        }

        if (!$roomCategoryId) {
            $this->lowestStartDate = null;
            $this->highestEndDate = null;
            return;
        }

        $roomCategory = RoomCategory::with([
            'occupancies.occupancy',
        ])->find($roomCategoryId);

        if (!$roomCategory) {
            return;
        }

        $this->lowestStartDate = $roomCategory->occupancies
            ->pluck('season.start_date')
            ->filter()
            ->min();

        $this->highestEndDate = $roomCategory->occupancies
            ->pluck('season.end_date')
            ->filter()
            ->max();

        // Dispatch event to update datepickers
        $this->dispatch('update-datepicker-range', [
            'lowestStartDate' => $this->lowestStartDate,
            'highestEndDate' => $this->highestEndDate,
        ]);

        // Resolve season using date range (with fallback)
        $seasonId = $this->resolveSeasonForDateRange();

        if (!$seasonId) {
            // No season available at all
            return;
        }

        $seasonRates = RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
            ->where('season_id', $seasonId)
            ->with('occupancy')
            ->get();

        if ($seasonRates->isEmpty()) {
            // No rates for resolved season, try to find any available season with rates
            $seasonRates = RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
                ->with('occupancy', 'season')
                ->get();

            if ($seasonRates->isEmpty()) {
                // No rates available for this room category at all
                return;
            }

            // Get the first available season with rates
            $seasonRates = $seasonRates->groupBy('season_id')->first();

            if (!$seasonRates) {
                return;
            }
        }

        // Auto-populate occupancies and rates from resolved season
        foreach ($seasonRates as $rateRow) {
            if (!$rateRow->occupancy) {
                continue;
            }

            $occupancyId = $rateRow->occupancy->occupancy_id;

            $this->occupancies[$occupancyId] = $rateRow->occupancy->title;

            $this->roomRatesData[] = [
                'occupancy_id' => $occupancyId,
                'rate'         => $rateRow->rate ?? 0,
                'weekend_rate' => $rateRow->weekend_rate ?? 0,
            ];
        }
    }
}

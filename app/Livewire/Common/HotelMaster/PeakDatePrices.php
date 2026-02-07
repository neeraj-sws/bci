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
    public $season_id;
    public $availableSeasons = [];
    public $seasonStartDate;
    public $seasonEndDate;

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'selected_room_categories' => 'required',
            'season_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $allowedSeasonIds = collect($this->availableSeasons)->pluck('season_id')->all();
                    if (!in_array((int) $value, $allowedSeasonIds, true)) {
                        $fail('Selected season is not available for this room category.');
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (!$this->seasonStartDate || !$this->seasonEndDate) {
                        return;
                    }

                    if ($value < $this->seasonStartDate || $value > $this->seasonEndDate) {
                        $fail('Start Date must be within the selected season range.');
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    if (!$this->seasonStartDate || !$this->seasonEndDate) {
                        return;
                    }

                    if ($value < $this->seasonStartDate || $value > $this->seasonEndDate) {
                        $fail('End Date must be within the selected season range.');
                    }
                },
            ],
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
        'season_id' => 'Season',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
    ];

    public function mount()
    {
        $this->loadDropdowns();
    }

    /**
     * Check if peak date overlaps with existing records
     * for the same hotel + room_category + season
     */
    private function checkDateOverlap($excludePeakDateId = null)
    {
        $roomCategoryId = is_array($this->selected_room_categories)
            ? (count($this->selected_room_categories) > 0 ? $this->selected_room_categories[0] : null)
            : $this->selected_room_categories;

        if (!$roomCategoryId || !$this->season_id || !$this->start_date || !$this->end_date) {
            return false;
        }

        $query = PeackDate::where('hotel_id', $this->hotel_id)
            ->where('room_category_id', $roomCategoryId)
            ->where('season_id', $this->season_id)
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    // Check if new date range overlaps with existing ones
                    $subQ->whereDate('start_date', '<=', $this->end_date)
                        ->whereDate('end_date', '>=', $this->start_date);
                });
            });

        // Exclude current record when updating
        if ($excludePeakDateId) {
            $query->where('peak_dates_id', '!=', $excludePeakDateId);
        }

        return $query->exists();
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

        // Check for date overlap
        if ($this->checkDateOverlap()) {
            $this->addError('start_date', 'Peak Date already exists for the selected room category and season within the selected date range.');
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
            'season_id' => $this->season_id,
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
        $this->season_id = $item->peakDate->season_id;
        if ($this->season_id) {
            $this->updatedSeasonId($this->season_id);
        }

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        // Check for date overlap (excluding current record)
        if ($this->checkDateOverlap($this->peak_date_id)) {
            $this->addError('start_date', 'Peak Date already exists for the selected room category and season within the selected date range.');
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
            'season_id' => $this->season_id,
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
            'season_id',
            'isEditing',
            'occupancies',
            'roomRatesData',
            'availableSeasons',
            'seasonStartDate',
            'seasonEndDate',
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
        $this->season_id = null;
        $this->availableSeasons = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;
        $this->occupancies = [];
        $this->roomRatesData = [];
    }

    /**
     * Watch start_date and reload rates if room category is selected
     */
    public function updatedStartDate($value)
    {
        return;
    }

    /**
     * Watch end_date and reload rates if room category is selected
     */
    public function updatedEndDate($value)
    {
        return;
    }

    public function updatedSelectedRoomCategories($roomCategoryId)
    {
        $this->occupancies   = [];
        $this->roomRatesData = [];
        $this->season_id = null;
        $this->availableSeasons = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;

        if (is_array($roomCategoryId)) {
            $roomCategoryId = count($roomCategoryId) > 0 ? $roomCategoryId[0] : null;
        }

        if (!$roomCategoryId) {
            return;
        }
        $this->availableSeasons = RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
            ->whereHas('season', function ($query) {
                $query->where('status', 1);
            })
            ->with('season')
            ->get()
            ->groupBy('season_id')
            ->map(function ($rows) {
                return $rows->first()->season;
            })
            ->filter()
            ->sortBy('start_date')
            ->map(function ($season) {
                return [
                    'season_id' => $season->seasons_id,
                    'title' => $season->title ?? $season->name,
                    'start_date' => $season->start_date,
                    'end_date' => $season->end_date,
                ];
            })
            ->values()
            ->toArray();
    }

    public function updatedSeasonId($seasonId)
    {
        $this->occupancies = [];
        $this->roomRatesData = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;

        $roomCategoryId = is_array($this->selected_room_categories)
            ? (count($this->selected_room_categories) > 0 ? $this->selected_room_categories[0] : null)
            : $this->selected_room_categories;

        if (!$roomCategoryId || !$seasonId) {
            return;
        }

        $selectedSeason = collect($this->availableSeasons)
            ->firstWhere('season_id', (int) $seasonId);

        if ($selectedSeason) {
            $this->seasonStartDate = $selectedSeason['start_date'] ?? null;
            $this->seasonEndDate = $selectedSeason['end_date'] ?? null;

            $this->dispatch('update-datepicker-range', [
                'lowestStartDate' => $this->seasonStartDate,
                'highestEndDate' => $this->seasonEndDate,
            ]);
        }

        $seasonRates = RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
            ->where('season_id', $seasonId)
            ->with('occupancy')
            ->get();

        if ($seasonRates->isEmpty()) {
            return;
        }

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

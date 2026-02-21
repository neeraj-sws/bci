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
use Carbon\Carbon;

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
    public $occupanciesByCategory = [];
    public $rates = [];
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
            'selected_room_categories' => 'required|array|min:1',
            'selected_room_categories.*' => 'exists:room_categoris,room_categoris_id',
            'season_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $allowedSeasonIds = collect($this->availableSeasons)->pluck('season_id')->all();
                    if (!in_array((int) $value, $allowedSeasonIds, true)) {
                        $fail('Selected season is not available for selected room categories.');
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
            'rates' => 'required|array|min:1',
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
            'rates.required' => 'Please configure rates for selected room categories.',
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

    private function hasDuplicatePeakDateForCategory($roomCategoryId, $excludePeakDateId = null)
    {
        if (!$roomCategoryId || !$this->hotel_id || !$this->start_date || !$this->end_date) {
            return false;
        }

        $query = PeackDate::where('hotel_id', $this->hotel_id)
            ->where('room_category_id', $roomCategoryId)
            ->whereDate('start_date', $this->start_date)
            ->whereDate('end_date', $this->end_date);

        if ($excludePeakDateId) {
            $query->where('peak_dates_id', '!=', (int) $excludePeakDateId);
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
        ])
            ->whereHas('peakDate', function ($q) {
                $q->where('status', 1)
                    ->whereHas('roomCategory', function ($roomQuery) {
                        $roomQuery->where('status', 1);
                    });
            });

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
            $this->roomCategories = $this->getRoomCategoriesWithRatesByHotel($hotelId);
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
                $this->occupanciesByCategory[$peakDate->room_category_id] = $peakDate->roomCategory->occupancies
                    ->pluck('occupancy.title', 'occupancy.occupancy_id')
                    ->toArray();
            } else {
                $this->occupanciesByCategory = [];
            }

            $this->rates = [];
        } else {
            $this->occupanciesByCategory = [];
            $this->rates = [];
        }
    }

    public function store()
    {
        $this->validate();

        $categoryIds = $this->normalizeCategoryIds($this->selected_room_categories);

        if (empty($categoryIds)) {
            $this->addError('selected_room_categories', 'Please select at least one room category.');
            return;
        }

        foreach ($categoryIds as $categoryId) {
            if ($this->hasDuplicatePeakDateForCategory($categoryId)) {
                $categoryName = $this->roomCategories[$categoryId] ?? 'selected category';
                $this->addError('selected_room_categories', "Peak Date already exists for {$categoryName} with same start/end dates.");
                return;
            }

            $categoryRates = $this->rates[$categoryId] ?? [];
            if (empty($categoryRates)) {
                $this->addError('rates', 'Please configure rates for all selected room categories.');
                return;
            }

            foreach ($categoryRates as $occupancyId => $row) {
                $weekdayRate = $row['rate'] ?? null;
                $weekendRate = $row['weekend_rate'] ?? null;

                if (!is_numeric($weekdayRate) || $weekdayRate < 0 || !is_numeric($weekendRate) || $weekendRate < 0) {
                    $this->addError('rates', 'Weekday and weekend rates must be numeric and non-negative.');
                    return;
                }
            }
        }

        foreach ($categoryIds as $categoryId) {
            $peakDate = PeackDate::create([
                'title' => ucwords($this->title),
                'hotel_id' => $this->hotel_id,
                'status' => $this->status,
                'room_category_id' => $categoryId,
                'season_id' => $this->season_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            foreach (($this->rates[$categoryId] ?? []) as $occupancyId => $row) {
                PeakDateRoomCategoryOccupances::create([
                    'peak_date_id' => $peakDate->id,
                    'occupancy_id' => $occupancyId,
                    'rate' => $row['rate'] ?? 0,
                    'weekend_rate' => $row['weekend_rate'] ?? 0,
                ]);
            }
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
        // Format dates properly for datepicker (Y-m-d format)
        $this->start_date = $item->peakDate->start_date
            ? Carbon::parse($item->peakDate->start_date)->format('Y-m-d')
            : null;
        $this->end_date = $item->peakDate->end_date
            ? Carbon::parse($item->peakDate->end_date)->format('Y-m-d')
            : null;
        $this->status = $item->peakDate->status ?? 1;

        $this->title = $item->peakDate->title;
        $this->hotel_id = $item->peakDate->hotel_id;

        $this->selected_room_categories = $item->peakDate->room_category_id ? [$item->peakDate->room_category_id] : [];

        $this->roomCategories = $this->getRoomCategoriesWithRatesByHotel($this->hotel_id);

        $this->occupanciesByCategory = [];
        $this->rates = [];

        // Load available seasons for the room category (without clearing existing data)
        $roomCategoryId = $item->peakDate->room_category_id;
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

        // Set season and load bounds
        $this->season_id = $item->peakDate->season_id;
        $selectedSeason = collect($this->availableSeasons)
            ->firstWhere('season_id', (int) $this->season_id);

        if ($selectedSeason) {
            $this->seasonStartDate = $selectedSeason['start_date'] ?? null;
            $this->seasonEndDate = $selectedSeason['end_date'] ?? null;

            $this->dispatch('update-datepicker-range', [
                'lowestStartDate' => $this->seasonStartDate,
                'highestEndDate' => $this->seasonEndDate,
            ]);
        }

        // Load existing peak date rates
        $existingRates = PeakDateRoomCategoryOccupances::where('peak_date_id', $this->peak_date_id)
            ->get()
            ->keyBy('occupancy_id');

        $categoryId = $item->peakDate->room_category_id;
        $seasonRates = RoomCategoryOccupances::where('room_category_id', $categoryId)
            ->where('season_id', $this->season_id)
            ->with('occupancy')
            ->get();

        foreach ($seasonRates as $rateRow) {
            if (!$rateRow->occupancy) {
                continue;
            }

            $occupancyId = $rateRow->occupancy->occupancy_id;
            $this->occupanciesByCategory[$categoryId][$occupancyId] = $rateRow->occupancy->title;

            $this->rates[$categoryId][$occupancyId] = [
                'rate' => isset($existingRates[$occupancyId]) ? $existingRates[$occupancyId]->rate : ($rateRow->rate ?? 0),
                'weekend_rate' => isset($existingRates[$occupancyId]) ? $existingRates[$occupancyId]->weekend_rate : ($rateRow->weekend_rate ?? 0),
            ];
        }

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $roomCategoryId = $this->normalizeCategoryIds($this->selected_room_categories)[0] ?? null;
        if (!$roomCategoryId) {
            $this->addError('selected_room_categories', 'Please select a room category.');
            return;
        }

        if ($this->hasDuplicatePeakDateForCategory($roomCategoryId, $this->peak_date_id)) {
            $this->addError('start_date', 'Peak Date already exists for the selected room category with same start/end dates.');
            return;
        }

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

        foreach (($this->rates[$roomCategoryId] ?? []) as $occupancyId => $row) {
            PeakDateRoomCategoryOccupances::create([
                'peak_date_id' => $this->peak_date_id,
                'occupancy_id' => $occupancyId,
                'rate' => $row['rate'] ?? 0,
                'weekend_rate' => $row['weekend_rate'] ?? 0,
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
            'occupanciesByCategory',
            'rates',
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
        $this->roomCategories = $this->getRoomCategoriesWithRatesByHotel($id);
        $this->selected_room_categories = [];
        $this->season_id = null;
        $this->availableSeasons = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;
        $this->occupanciesByCategory = [];
        $this->rates = [];
    }

    private function getRoomCategoriesWithRatesByHotel($hotelId): array
    {
        if (!$hotelId) {
            return [];
        }

        return RoomCategory::where('hotel_id', $hotelId)
            ->where('status', 1)
            ->whereHas('occupancies')
            ->orderBy('title')
            ->pluck('title', 'room_categoris_id')
            ->toArray();
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
        $this->occupanciesByCategory = [];
        $this->rates = [];
        $this->season_id = null;
        $this->availableSeasons = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;

        $categoryIds = $this->normalizeCategoryIds($roomCategoryId);
        $this->selected_room_categories = $categoryIds;

        if (empty($categoryIds)) {
            return;
        }

        $this->availableSeasons = $this->getCommonSeasonsForCategories($categoryIds);
    }

    public function updatedSeasonId($seasonId)
    {
        $this->occupanciesByCategory = [];
        $this->rates = [];
        $this->seasonStartDate = null;
        $this->seasonEndDate = null;

        $categoryIds = $this->normalizeCategoryIds($this->selected_room_categories);

        if (empty($categoryIds) || !$seasonId) {
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

        foreach ($categoryIds as $categoryId) {
            $seasonRates = RoomCategoryOccupances::where('room_category_id', $categoryId)
                ->where('season_id', $seasonId)
                ->with('occupancy')
                ->get();

            foreach ($seasonRates as $rateRow) {
                if (!$rateRow->occupancy) {
                    continue;
                }

                $occupancyId = $rateRow->occupancy->occupancy_id;
                $this->occupanciesByCategory[$categoryId][$occupancyId] = $rateRow->occupancy->title;
                $this->rates[$categoryId][$occupancyId] = [
                    'rate' => $rateRow->rate ?? 0,
                    'weekend_rate' => $rateRow->weekend_rate ?? 0,
                ];
            }
        }
    }

    private function normalizeCategoryIds($value): array
    {
        $ids = is_array($value) ? $value : [$value];

        return collect($ids)
            ->filter(fn($id) => $id !== null && $id !== '')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function getSeasonsForCategory($categoryId): array
    {
        return RoomCategoryOccupances::where('room_category_id', $categoryId)
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
                    'season_id' => (int) $season->seasons_id,
                    'title' => $season->title ?? $season->name,
                    'start_date' => $season->start_date,
                    'end_date' => $season->end_date,
                ];
            })
            ->values()
            ->toArray();
    }

    private function getCommonSeasonsForCategories(array $categoryIds): array
    {
        $commonSeasonMap = null;

        foreach ($categoryIds as $categoryId) {
            $seasonMap = collect($this->getSeasonsForCategory($categoryId))
                ->keyBy('season_id')
                ->toArray();

            if ($commonSeasonMap === null) {
                $commonSeasonMap = $seasonMap;
                continue;
            }

            $commonIds = array_intersect(array_keys($commonSeasonMap), array_keys($seasonMap));
            $commonSeasonMap = array_intersect_key($commonSeasonMap, array_flip($commonIds));
        }

        return collect($commonSeasonMap ?? [])
            ->sortBy('start_date')
            ->values()
            ->all();
    }
}

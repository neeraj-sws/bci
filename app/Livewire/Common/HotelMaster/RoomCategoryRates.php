<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\RoomCategory;
use App\Models\RoomCategoryOccupances;
use App\Models\Season;
use App\Models\Occupancy;
use App\Models\Hotel;
use App\Models\RateTypes;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.hotel-app')]
class RoomCategoryRates extends Component
{
    use WithPagination;

    public $pageTitle = 'Room Category Rates';

    // Main Form
    public $hotel_id;
    public $room_category_id;
    public $season_id;
    public $selected_occupancies = [];
    public $roomRatesData = [];
    public $roomCategories = [];
    public $seasons = [];
    public $occupancies = [];
    public $hotels = [];
    public $isEditing = false;

    // Filters
    public $filter_hotel_id;
    public $filter_room_category_id;
    public $filter_season_id;
    public $filter_room_categories = [];
    public $sortBy = 'room_category_id';
    public $sortDirection = 'asc';

    // Add Room Category Modal
    public $showAddCategoryModal = false;
    public $newCategoryTitle = '';
    public $newCategoryRateType = '';
    public $newCategoryStatus = 1;
    public $rateTypes = [];

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'room_category_id' => 'required|exists:room_categoris,room_categoris_id',
            'season_id' => 'required|exists:seasons,seasons_id',
            'selected_occupancies' => 'required|array|min:1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.occupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate' => 'required|numeric|min:0',
            'roomRatesData.*.weekend_rate' => 'required|numeric|min:0',
        ];
    }

    protected function newCategoryRules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'newCategoryTitle' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = RoomCategory::where('hotel_id', $this->hotel_id)
                        ->where('title', $value)
                        ->exists();
                    if ($exists) {
                        $fail('A room category with this title already exists for the selected hotel.');
                    }
                },
            ],
            'newCategoryRateType' => 'nullable|exists:rate_types,rate_type_id',
            'newCategoryStatus' => 'required|in:0,1',
        ];
    }

    public function mount(): void
    {
        $this->hotels = Hotel::where('status', 1)
            ->select('hotels_id', 'name')
            ->orderBy('name')
            ->get();
        $this->seasons = Season::where('status', 1)
            ->select('seasons_id', 'name', 'start_date', 'end_date')
            ->orderBy('start_date')
            ->get();
        $this->occupancies = Occupancy::where('status', 1)
            ->orderBy('title')
            ->pluck('title', 'occupancy_id')
            ->toArray();
        $this->rateTypes = RateTypes::where('status', 1)
            ->select('rate_type_id', 'title')
            ->orderBy('title')
            ->get();
    }

    public function render()
    {
        // Build room category IDs for filter based on selected hotel
        $filterCategoryIds = null;
        if ($this->filter_hotel_id) {
            $filterCategoryIds = RoomCategory::select('room_categoris_id')
                ->where('hotel_id', $this->filter_hotel_id)
                ->where('status', 1)
                ->pluck('room_categoris_id')
                ->toArray();
        }

        $rateSets = RoomCategoryOccupances::query()
            ->select('room_category_id', 'season_id')
            ->when($filterCategoryIds, fn($q) => $q->whereIn('room_category_id', $filterCategoryIds))
            ->when($this->filter_room_category_id && !$this->filter_hotel_id, fn($q) => $q->where('room_category_id', $this->filter_room_category_id))
            ->when($this->filter_room_category_id && $this->filter_hotel_id, fn($q) => $q->where('room_category_id', $this->filter_room_category_id))
            ->when($this->filter_season_id, fn($q) => $q->where('season_id', $this->filter_season_id))
            ->groupBy('room_category_id', 'season_id')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->with([
                'roomCategory:room_categoris_id,title,hotel_id,status',
                'roomCategory.rommtCategoryHotel:hotels_id,name',
                'season:seasons_id,name,start_date,end_date'
            ])
            ->paginate(10);

        $roomCategoryIds = $rateSets->pluck('room_category_id')->unique();
        $seasonIds = $rateSets->pluck('season_id')->filter()->unique();
        $hasNullSeason = $rateSets->contains(fn($set) => is_null($set->season_id));

        $rateDetailsQuery = RoomCategoryOccupances::select('room_category_id', 'season_id', 'occupancy_id', 'rate', 'weekend_rate')
            ->with('occupancy:occupancy_id,title')
            ->whereIn('room_category_id', $roomCategoryIds);

        if ($seasonIds->isNotEmpty()) {
            $rateDetailsQuery->where(function ($query) use ($seasonIds, $hasNullSeason) {
                $query->whereIn('season_id', $seasonIds);
                if ($hasNullSeason) {
                    $query->orWhereNull('season_id');
                }
            });
        } elseif ($hasNullSeason) {
            $rateDetailsQuery->whereNull('season_id');
        }

        $rateDetails = $rateDetailsQuery->get()
            ->groupBy(fn($rate) => $rate->room_category_id . '-' . $rate->season_id);

        return view('livewire.common.hotel-master.room-category-rates', [
            'rateSets' => $rateSets,
            'rateDetails' => $rateDetails,
            'filterRoomCategories' => $this->filter_room_categories,
        ]);
    }

    public function save(): void
    {
        $this->validate();
        $this->ensureRateCountMatchesOccupancy();

        RoomCategoryOccupances::where('room_category_id', $this->room_category_id)
            ->where('season_id', $this->season_id)
            ->delete();

        $insertData = collect($this->roomRatesData)->map(fn($rate) => [
            'room_category_id' => $this->room_category_id,
            'season_id' => $this->season_id,
            'occupancy_id' => $rate['occupancy_id'],
            'rate' => $rate['rate'],
            'weekend_rate' => $rate['weekend_rate'] ?? 0,
        ])->toArray();

        RoomCategoryOccupances::insert($insertData);

        $this->toast($this->isEditing ? 'Updated Successfully' : 'Added Successfully');
        $this->resetForm();
    }

    public function edit($roomCategoryId, $seasonId): void
    {
        $this->resetValidation();

        // Get room category with hotel_id
        $roomCategory = RoomCategory::select('room_categoris_id', 'hotel_id')
            ->find($roomCategoryId);

        if (!$roomCategory) {
            return;
        }

        // Get existing rates
        $existing = RoomCategoryOccupances::select('occupancy_id', 'rate', 'weekend_rate')
            ->where('room_category_id', $roomCategoryId)
            ->where('season_id', $seasonId)
            ->get();

        if ($existing->isEmpty()) {
            return;
        }

        // Set hotel first (this will trigger loadRoomCategoriesForHotel)
        $this->hotel_id = $roomCategory->hotel_id;
        $this->loadRoomCategoriesForHotel();

        // Now set room category and season
        $this->room_category_id = $roomCategoryId;
        $this->season_id = $seasonId;

        // Set selected occupancies and rates
        $this->selected_occupancies = $existing->pluck('occupancy_id')->toArray();
        $this->roomRatesData = $existing->map(fn($rate) => [
            'occupancy_id' => $rate->occupancy_id,
            'rate' => $rate->rate,
            'weekend_rate' => $rate->weekend_rate ?? 0,
        ])->toArray();

        $this->isEditing = true;
    }

    public function deleteRates($roomCategoryId, $seasonId): void
    {
        RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
            ->where('season_id', $seasonId)
            ->delete();
        if ((int)$this->room_category_id === (int)$roomCategoryId && (int)$this->season_id === (int)$seasonId) {
            $this->resetForm();
        }

        $this->toast('Deleted Successfully');
    }

    public function updatedHotelId(): void
    {
        $this->resetValidation();
        $this->loadRoomCategoriesForHotel();
        $this->room_category_id = null;
        $this->season_id = null;
        $this->selected_occupancies = [];
        $this->roomRatesData = [];
        $this->isEditing = false;
    }

    public function updatedRoomCategoryId(): void
    {
        $this->resetValidation();
        // Only prefill if we have both room_category_id and season_id
        if ($this->room_category_id && $this->season_id) {
            $this->prefillFromExisting();
        }
    }

    public function updatedSeasonId(): void
    {
        $this->resetValidation();
        // Only prefill if we have both room_category_id and season_id
        if ($this->room_category_id && $this->season_id) {
            $this->prefillFromExisting();
        }
    }

    public function updatedFilterHotelId(): void
    {
        $this->loadFilterRoomCategories();
        $this->filter_room_category_id = null;
        $this->resetPage();
    }

    public function updatedFilterRoomCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSeasonId(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedOccupancies(): void
    {
        if (empty($this->selected_occupancies)) {
            $this->roomRatesData = [];
            return;
        }

        $this->resetValidation(['selected_occupancies', 'roomRatesData', 'roomRatesData.*']);

        $currentOccupancies = collect($this->roomRatesData)->pluck('occupancy_id')->toArray();

        foreach ($this->selected_occupancies as $occupancyId) {
            if (!in_array($occupancyId, $currentOccupancies)) {
                $this->roomRatesData[] = [
                    'occupancy_id' => $occupancyId,
                    'rate' => 0,
                    'weekend_rate' => 0,
                ];
            }
        }

        $this->roomRatesData = array_values(array_filter($this->roomRatesData, function ($item) {
            return in_array($item['occupancy_id'], $this->selected_occupancies);
        }));
    }

    public function sortby($field): void
    {
        $allowed = ['room_category_id', 'season_id'];
        if (!in_array($field, $allowed, true)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function prefillFromExisting(): void
    {
        if (!$this->room_category_id || !$this->season_id) {
            return;
        }

        $existing = RoomCategoryOccupances::select('occupancy_id', 'rate', 'weekend_rate')
            ->where('room_category_id', $this->room_category_id)
            ->where('season_id', $this->season_id)
            ->get();

        if ($existing->isEmpty()) {
            $this->selected_occupancies = [];
            $this->roomRatesData = [];
            $this->isEditing = false;
            return;
        }

        $this->selected_occupancies = $existing->pluck('occupancy_id')->toArray();
        $this->roomRatesData = $existing->map(fn($rate) => [
            'occupancy_id' => $rate->occupancy_id,
            'rate' => $rate->rate,
            'weekend_rate' => $rate->weekend_rate ?? 0,
        ])->toArray();
        $this->isEditing = true;
    }

    private function ensureRateCountMatchesOccupancy(): void
    {
        $selected = collect($this->selected_occupancies)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $rates = collect($this->roomRatesData)
            ->pluck('occupancy_id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        // 1. Empty check
        if ($selected->isEmpty()) {
            throw ValidationException::withMessages([
                'selected_occupancies' => 'Please select at least one occupancy.',
            ]);
        }

        // 2. Missing rates
        $missing = $selected->diff($rates);
        if ($missing->isNotEmpty()) {
            throw ValidationException::withMessages([
                'roomRatesData' => 'Please enter rates for all selected occupancies.',
            ]);
        }

        // 3. Extra rates (orphan rows)
        $extra = $rates->diff($selected);
        if ($extra->isNotEmpty()) {
            throw ValidationException::withMessages([
                'roomRatesData' => 'Some rate entries do not match the selected occupancies.',
            ]);
        }
    }

    public function openAddCategoryModal(): void
    {
        if (!$this->hotel_id) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => 'Please select a hotel first.',
            ]);
            return;
        }

        $this->resetValidation();
        $this->newCategoryTitle = '';
        $this->newCategoryRateType = '';
        $this->newCategoryStatus = 1;
        $this->showAddCategoryModal = true;
    }

    public function closeAddCategoryModal(): void
    {
        $this->showAddCategoryModal = false;
        $this->resetValidation(['newCategoryTitle', 'newCategoryRateType', 'newCategoryStatus']);
    }

    public function saveNewCategory(): void
    {
        $this->validate($this->newCategoryRules());

        $newCategory = RoomCategory::create([
            'hotel_id' => $this->hotel_id,
            'title' => $this->newCategoryTitle,
            'rate_type_id' => $this->newCategoryRateType ?: null,
            'status' => $this->newCategoryStatus,
        ]);

        $this->loadRoomCategoriesForHotel();
        $this->room_category_id = $newCategory->id;
        $this->closeAddCategoryModal();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Room Category created successfully.',
        ]);
    }

    private function loadRoomCategoriesForHotel(): void
    {
        if (!$this->hotel_id) {
            $this->roomCategories = collect();
            return;
        }

        $this->roomCategories = RoomCategory::select('room_categoris_id', 'title', 'hotel_id')
            ->where('hotel_id', $this->hotel_id)
            ->where('status', 1)
            ->with('rommtCategoryHotel:hotels_id,name')
            ->orderBy('title')
            ->get();
    }

    private function loadFilterRoomCategories(): void
    {
        if (!$this->filter_hotel_id) {
            $this->filter_room_categories = [];
            return;
        }

        $this->filter_room_categories = RoomCategory::select('room_categoris_id', 'title')
            ->where('hotel_id', $this->filter_hotel_id)
            ->where('status', 1)
            ->orderBy('title')
            ->get()
            ->toArray();
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->reset([
            'room_category_id',
            'season_id',
            'selected_occupancies',
            'roomRatesData',
            'isEditing',
        ]);
    }

    private function toast(string $msg): void
    {
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' ' . $msg,
        ]);
    }

    public function updating()
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->filter_hotel_id = null;
        $this->filter_room_category_id = null;
        $this->filter_season_id = null;
        $this->filter_room_categories = [];
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $item = RoomCategory::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }
}

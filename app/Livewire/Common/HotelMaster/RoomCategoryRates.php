<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\RoomCategory;
use App\Models\RoomCategoryOccupances;
use App\Models\Season;
use App\Models\Occupancy;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.hotel-app')]
class RoomCategoryRates extends Component
{
    use WithPagination;

    public $pageTitle = 'Room Category Rates';

    public $room_category_id;
    public $season_id;
    public $selected_occupancies = [];
    public $roomRatesData = [];
    public $roomCategories = [];
    public $seasons = [];
    public $occupancies = [];
    public $isEditing = false;

    public $filter_room_category_id;
    public $filter_season_id;
    public $sortBy = 'room_category_id';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'room_category_id' => 'required|exists:room_categoris,room_categoris_id',
            'season_id' => 'required|exists:seasons,seasons_id',
            'selected_occupancies' => 'required|array|min:1',
            'roomRatesData' => 'required|array|min:1',
            'roomRatesData.*.occupancy_id' => 'required|distinct|exists:occupances,occupancy_id',
            'roomRatesData.*.rate' => 'required|numeric|min:0',
            'roomRatesData.*.weekend_rate' => 'required|numeric|min:0',
        ];
    }

    public function mount(): void
    {
        $this->roomCategories = RoomCategory::where('status', 1)
            ->with('rommtCategoryHotel')
            ->orderBy('title')
            ->get();
        $this->seasons = Season::where('status', 1)->orderBy('start_date')->get();
        $this->occupancies = Occupancy::where('status', 1)->pluck('title', 'occupancy_id')->toArray();
    }

    public function render()
    {
        $rateSets = RoomCategoryOccupances::query()
            ->select('room_category_id', 'season_id')
            ->when($this->filter_room_category_id, fn($q) => $q->where('room_category_id', $this->filter_room_category_id))
            ->when($this->filter_season_id, fn($q) => $q->where('season_id', $this->filter_season_id))
            ->groupBy('room_category_id', 'season_id')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->with(['roomCategory', 'season'])
            ->paginate(10);

        $seasonIds = $rateSets->pluck('season_id')->filter()->unique()->values();
        $hasNullSeason = $rateSets->contains(fn($set) => is_null($set->season_id));

        $rateDetailsQuery = RoomCategoryOccupances::with('occupancy')
            ->whereIn('room_category_id', $rateSets->pluck('room_category_id'));

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
        ]);
    }

    public function save(): void
    {
        $this->validate();
        $this->ensureRateCountMatchesOccupancy();

        RoomCategoryOccupances::where('room_category_id', $this->room_category_id)
            ->where('season_id', $this->season_id)
            ->delete();

        foreach ($this->roomRatesData as $rate) {
            RoomCategoryOccupances::create([
                'room_category_id' => $this->room_category_id,
                'season_id' => $this->season_id,
                'occupancy_id' => $rate['occupancy_id'],
                'rate' => $rate['rate'],
                'weekend_rate' => $rate['weekend_rate'] ?? 0,
            ]);
        }

        $this->toast($this->isEditing ? 'Updated Successfully' : 'Added Successfully');
        $this->resetForm();
    }

    public function edit($roomCategoryId, $seasonId): void
    {
        $this->resetValidation();
        $existing = RoomCategoryOccupances::where('room_category_id', $roomCategoryId)
            ->where('season_id', $seasonId)
            ->get();

        if ($existing->isEmpty()) {
            return;
        }

        $this->room_category_id = $roomCategoryId;
        $this->season_id = $seasonId;
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

        // reset form if the current selection was removed
        if ((int)$this->room_category_id === (int)$roomCategoryId && (int)$this->season_id === (int)$seasonId) {
            $this->resetForm();
        }

        $this->toast('Deleted Successfully');
    }

    public function updatedRoomCategoryId(): void
    {
        $this->resetValidation();
        $this->prefillFromExisting();
    }

    public function updatedSeasonId(): void
    {
        $this->resetValidation();
        $this->prefillFromExisting();
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

        $existing = RoomCategoryOccupances::where('room_category_id', $this->room_category_id)
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
}

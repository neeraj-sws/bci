<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\PeackDate;
use App\Models\ChildPolicy;
use App\Services\Season\HotelSeasonService;

class KpiCards extends Component
{
    public $hotelId;
    public $selectedSeason = '';
    public $totalRoomCategories = 0;
    public $totalPeakDates = 0;
    public $totalChildPolicies = 0;
    public $startingPrice = 0;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;


        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = $defaultSeason?->seasons_id ?? '';

        $this->loadKpiData();
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
        $this->loadKpiData();
    }

    public function loadKpiData()
    {
        $this->totalRoomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->count();

        $peakDateQuery = PeackDate::where('hotel_id', $this->hotelId)
            ->where('status', 1);

        if ($this->selectedSeason) {
            $peakDateQuery->where('season_id', $this->selectedSeason);
        }

        $this->totalPeakDates = $peakDateQuery->count();

        $childPolicyQuery = ChildPolicy::where('hotel_id', $this->hotelId)
            ->where('status', 1);

        if ($this->selectedSeason) {
            $childPolicyQuery->where(function($query) {
                $query->whereHas('roomCategory.occupancies', function($q) {
                    $q->where('season_id', $this->selectedSeason);
                })
                ->orWhereHas('peakDate', function($q) {
                    $q->where('season_id', $this->selectedSeason);
                });
            });
        }

        $this->totalChildPolicies = $childPolicyQuery->count();


        $roomCategoriesQuery = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with(['occupancies' => function($query) {
                if ($this->selectedSeason) {
                    $query->where('season_id', $this->selectedSeason);
                }
            }]);

        $lowestPrice = $roomCategoriesQuery->get()
            ->flatMap(function($room) {
                return $room->occupancies->pluck('rate');
            })
            ->min();

        $this->startingPrice = $lowestPrice ?? 0;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.kpi-cards');
    }
}

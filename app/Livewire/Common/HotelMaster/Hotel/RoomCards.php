<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\RoomCategory;
use App\Services\Season\HotelSeasonService;
use Carbon\Carbon;

class RoomCards extends Component
{
    public $hotelId;
    public $roomCategories;
    public $expandedRoom = null;
    public $selectedSeason = null;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;
        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = $defaultSeason?->seasons_id ?? '';
        $this->loadRoomCategories();
    }

    private function loadRoomCategories()
    {
        $this->roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with([
                'childPolicies' => function ($query) {
                    $query->where('status', 1);
                },
                'peakDates' => function ($query) {
                    $query->where('status', 1)
                        ->with('occupancies')
                        ->orderBy('start_date', 'asc');
                },
                'occupancies.occupancy'
            ])
            ->get();
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
    }

    public function toggleRoom($roomId)
    {
        if ($this->expandedRoom === $roomId) {
            $this->expandedRoom = null;
        } else {
            $this->expandedRoom = $roomId;
        }
    }

    public function getFilteredOccupancies($room)
    {
        return $room->occupancies->filter(function ($occ) {
            return is_null($occ->season_id) || $occ->season_id == $this->selectedSeason;
        });
    }

    public function getFilteredPeakDates($room)
    {
        return $room->peakDates
            ->filter(function ($peak) {
                // Filter by selected season - check peak_date's season_id directly
                if ($this->selectedSeason) {
                    return $peak->season_id == $this->selectedSeason;
                }
                return true;
            })
            ->filter(function ($peak) {
                // Filter out past peak dates - check end_date from peak_dates table
                if (!$peak->end_date) return true;
                return Carbon::parse($peak->end_date)->gte(now());
            })
            ->sortBy(function ($peak) {
                return $peak->start_date ?? '';
            });
    }


    public function getUpcomingPeaks($room)
    {
        return $this->getFilteredPeakDates($room)->take(1);
    }

    public function getPeakDateRange($peak)
    {
        return [
            'start_date' => $peak->start_date ?? null,
            'end_date' => $peak->end_date ?? null,
        ];
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-cards');
    }
}

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
                $filteredOccs = $peak->occupancies->filter(function ($occ) {
                    return is_null($occ->season_id) || $occ->season_id == $this->selectedSeason;
                });

                if ($filteredOccs->count() === 0) return false;
                $hasUpcoming = $filteredOccs->filter(function ($occ) {
                    if (!$occ->end_date) return true;
                    return Carbon::parse($occ->end_date)->gte(now());
                })->count() > 0;

                return $hasUpcoming;
            })
            ->sortBy(function ($peak) {
                $occupancies = $this->getPeakOccupancies($peak);
                return $occupancies->min('start_date') ?? '';
            });
    }


    public function getUpcomingPeaks($room)
    {
        return $this->getFilteredPeakDates($room)->take(1);
    }


    public function getPeakOccupancies($peak)
    {
        return $peak->occupancies->filter(function ($occ) {
            return is_null($occ->season_id) || $occ->season_id == $this->selectedSeason;
        });
    }

    public function getPeakDateRange($peak)
    {
        $occupancies = $this->getPeakOccupancies($peak);
        $firstOcc = $occupancies->first();

        return [
            'start_date' => $firstOcc->start_date ?? null,
            'end_date' => $firstOcc->end_date ?? null,
        ];
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-cards');
    }
}

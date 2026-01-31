<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\RoomCategory;
use App\Services\Season\HotelSeasonService;
use Carbon\Carbon;

class RoomPeakTimeline extends Component
{
    public $hotelId;
    public $roomCategories;
    public $selectedSeason = null;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;

        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = $defaultSeason?->seasons_id ?? '';
        $this->loadRoomCategories();
    }

    public function loadRoomCategories()
    {
        $this->roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with([
                'peakDates' => function ($q) {
                    $q->where('status', 1)
                        ->with('occupancies')
                        ->orderBy('title', 'asc');
                }
            ])
            ->get()
            ->map(function ($room) {
                // Filter peak dates by selected season
                $room->peakDates = $room->peakDates->filter(function ($peak) {
                    // Get occupancies matching the selected season
                    $filteredOccs = $peak->occupancies->filter(function ($occ) {
                        return is_null($occ->season_id) 
                            || $occ->season_id == $this->selectedSeason;
                    });

                    // Only include peak dates that have at least one matching occupancy
                    if ($filteredOccs->count() === 0) {
                        return false;
                    }

                    // Filter out past peak dates (check end_date from occupancies)
                    $hasUpcoming = $filteredOccs->filter(function ($occ) {
                        if (!$occ->end_date) return true;
                        return Carbon::parse($occ->end_date)->gte(now());
                    })->count() > 0;

                    return $hasUpcoming;
                })
                ->sortBy(function ($peak) {
                    // Sort by earliest start_date from occupancies
                    $occupancies = $peak->occupancies->filter(function ($occ) {
                        return is_null($occ->season_id) 
                            || $occ->season_id == $this->selectedSeason;
                    });
                    return $occupancies->min('start_date') ?? '';
                })
                ->values();

                return $room;
            });
    }


    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
        $this->loadRoomCategories();
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-peak-timeline');
    }
}

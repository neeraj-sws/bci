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
                    // Sort by earliest start_date from peak_dates table
                    return $peak->start_date ?? '';
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

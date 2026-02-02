<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\RoomCategory;
use App\Services\Season\HotelSeasonService;
use Carbon\Carbon;

class PeakDates extends Component
{
    public $hotelId;
    public $roomCategories;
    public $expandedPeak = null;
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
                'peakDates' => function ($query) {
                    $query->where('status', 1)
                        ->with([
                            'occupancies.occupancy',
                            'childPolicies' => function ($q) {
                                $q->where('status', 1);
                            }
                        ])
                        ->orderBy('title', 'asc');
                }
            ])
            ->orderBy('title', 'asc')
            ->get();
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
    }

    public function togglePeak($peakId)
    {
        if ($this->expandedPeak === $peakId) {
            $this->expandedPeak = null;
        } else {
            $this->expandedPeak = $peakId;
        }
    }

    public function getFilteredRoomCategories()
    {
        return $this->roomCategories->filter(function ($room) {
            return $this->getFilteredPeakDates($room)->count() > 0;
        });
    }

    public function getFilteredPeakDates($room)
    {
        return $room->peakDates->filter(function ($peak) {
            $filteredOccs = $this->getPeakOccupancies($peak);
            return $filteredOccs->count() > 0;
        });
    }


    public function getPeakOccupancies($peak)
    {
        return $peak->occupancies;
    }


    public function getChildPoliciesGrouped($peak)
    {
        if (!$peak->childPolicies || $peak->childPolicies->count() === 0) {
            return collect();
        }
        return $peak->childPolicies->groupBy('free_child_age');
    }

    public function getPeakDateRange($peak)
    {
        return [
            'start_date' => $peak->start_date ?? null,
            'end_date' => $peak->end_date ?? null,
        ];
    }

    public function getPeakSurcharge($peak)
    {
        return $peak->extra_amount ?? 0;
    }

    public function calculateDays($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return 0;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return (int) ($start->diffInDays($end) + 1);
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.peak-dates');
    }
}

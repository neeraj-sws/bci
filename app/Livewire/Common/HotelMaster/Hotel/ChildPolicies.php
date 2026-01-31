<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChildPolicy;
use App\Services\Season\HotelSeasonService;

class ChildPolicies extends Component
{
    public $hotelId;
    public $selectedSeason = null;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;
        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = $defaultSeason?->seasons_id ?? '';
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
    }

    public function getRegularPolicies()
    {
        $query = ChildPolicy::where('hotel_id', $this->hotelId)
            ->whereNull('peak_date_id');

        if ($this->selectedSeason && $this->selectedSeason !== '') {
            $query->whereHas('roomCategory.occupancies', function ($q) {
                $q->where('season_id', $this->selectedSeason);
            });
        }

        return $query
            ->with(['roomCategory'])
            ->orderBy('free_child_age', 'asc')
            ->get()
            ->groupBy('room_category_id');
    }
    public function getPeakPolicies()
    {
        $query = ChildPolicy::where('hotel_id', $this->hotelId)
            ->whereNotNull('peak_date_id');

        if ($this->selectedSeason && $this->selectedSeason !== '') {
            $query->whereHas('peakDate.occupancies', function ($query) {
                $query->where('season_id', $this->selectedSeason);
            });
        }

        $peakPolicies = $query
            ->with([
                'roomCategory',
                'peakDate'
            ])
            ->orderBy('free_child_age', 'asc')
            ->get();

        return $peakPolicies->groupBy('peak_date_id');
    }

    public function getAllChildPolicies()
    {
        $regularPolicies = ChildPolicy::where('hotel_id', $this->hotelId)
            ->whereNull('peak_date_id')
            ->get();

        $peakPolicies = $this->getPeakPolicies()->flatten();

        return $regularPolicies->concat($peakPolicies);
    }

    public function getRoomCategoriesWithPolicies()
    {
        $allPolicies = $this->getAllChildPolicies();
        return $allPolicies->groupBy('room_category_id');
    }

    public function render()
    {
        $regularPolicies = $this->getRegularPolicies();
        $peakPolicies = $this->getPeakPolicies();
        $allChildPolicies = $this->getAllChildPolicies();
        $groupedByRoom = $this->getRoomCategoriesWithPolicies();


        $regularPoliciesCount = $regularPolicies->flatten()->count();
        $peakPoliciesCount = $peakPolicies->flatten()->count();

        return view('livewire.common.hotel-master.hotel.child-policies', [
            'childPolicies'         => $allChildPolicies,
            'groupedByRoom'         => $groupedByRoom,
            'regularPolicies'       => $regularPolicies,
            'peakPolicies'          => $peakPolicies,
            'regularPoliciesCount'  => $regularPoliciesCount,
            'peakPoliciesCount'     => $peakPoliciesCount,
        ]);
    }
}

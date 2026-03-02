<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChildPolicy;
use App\Services\Season\HotelSeasonService;

class ChildPolicies extends Component
{
    private const SEASON_SESSION_KEY_PREFIX = 'hotel_selected_season_';
    public $hotelId;
    public $selectedSeason = null;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;
        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = session(
            self::SEASON_SESSION_KEY_PREFIX . $this->hotelId,
            $defaultSeason?->seasons_id ?? ''
        );
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
    }

    public function getRegularPolicies()
    {
        $query = ChildPolicy::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->whereNull('peak_date_id');

        if ($this->selectedSeason && $this->selectedSeason !== '') {
            $query->whereHas('roomCategory.occupancies', function ($q) {
                $q->where('season_id', $this->selectedSeason);
            });
        }
        
         $query->whereHas('roomCategory', function ($q) {
            $q->where('status', 1);
        });

        return $query
            ->with(['roomCategory'])
            ->orderBy('free_child_age', 'asc')
            ->get()
            ->groupBy('room_category_id');
    }
    public function getPeakPolicies()
    {
        $query = ChildPolicy::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->whereNotNull('peak_date_id');
            
        $query->whereHas('peakDate', function ($query) {
            $query->where('status', 1);

            if ($this->selectedSeason && $this->selectedSeason !== '') {
                $query->where('season_id', $this->selectedSeason);
            }
        });

        $query->whereHas('roomCategory', function ($q) {
            $q->where('status', 1);
        });

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
        return $this->getRegularPolicies()
            ->flatten()
            ->concat($this->getPeakPolicies()->flatten());

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

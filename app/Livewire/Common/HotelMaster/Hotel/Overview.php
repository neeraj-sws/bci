<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Hotel;
use App\Models\PeackDate;
use App\Models\RoomCategory;
use App\Services\Season\HotelSeasonService;

class Overview extends Component
{
    public $hotelId;
    public $hotel;
    public $marketingCompany;
    public $parentChain;
    public $selectedSeason = '';
    public $lowestSinglePrice = 0;
    public $lowestDoublePrice = 0;
    public $highestPeakSurcharge = 0;
    public $peakSinglePrice = 0;
    public $peakDoublePrice = 0;

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;

        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = $defaultSeason?->seasons_id ?? '';

        $this->loadData();
    }

    #[On('seasonChanged')]
    public function updateSeason($seasonId)
    {
        $this->selectedSeason = $seasonId;
        $this->calculatePricingSnapshot();
    }

    public function loadData()
    {
        $this->hotel = Hotel::with(['hotelType', 'hotelCategory', 'hotelMealType', 'marketingCompany', 'parentChain', 'country', 'state', 'city', 'park'])->find($this->hotelId);


        $this->marketingCompany = $this->hotel->marketingCompany;
        $this->parentChain = $this->hotel->parentChain;


        $this->calculatePricingSnapshot();
    }

    public function calculatePricingSnapshot()
    {
        $roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with(['occupancies' => function ($query) {
                if ($this->selectedSeason) {
                    $query->where('season_id', $this->selectedSeason);
                }
                return $query->with('occupancy');
            }])
            ->get();

        $allRates = $roomCategories->flatMap(function ($room) {
            return $room->occupancies;
        });


        $singleRates = $allRates->where('occupancy.title', 'Single')->pluck('rate');
        $doubleRates = $allRates->where('occupancy.title', 'Double')->pluck('rate');

        $this->lowestSinglePrice = $singleRates->min() ?? 0;
        $this->lowestDoublePrice = $doubleRates->min() ?? 0;

        $peakDates = PeackDate::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with(['occupancies' => function ($query) {
                if ($this->selectedSeason) {
                    $query->where('season_id', $this->selectedSeason);
                }
                return $query->with('occupancy');
            }])
            ->get();

        $maxSurcharge = 0;
        $peakSingleRates = collect();
        $peakDoubleRates = collect();

        foreach ($peakDates as $peakDate) {
            foreach ($peakDate->occupancies as $peakOccupancy) {
                if ($peakOccupancy->occupancy && $peakOccupancy->occupancy->title === 'Single') {
                    $peakSingleRates->push($peakOccupancy->rate);
                }

                if ($peakOccupancy->occupancy && $peakOccupancy->occupancy->title === 'Double') {
                    $peakDoubleRates->push($peakOccupancy->rate);
                }

                $baseRate = $allRates->where('occupancy_id', $peakOccupancy->occupancy_id)->pluck('rate')->min();

                if ($baseRate) {
                    $surcharge = $peakOccupancy->rate - $baseRate;

                    if ($surcharge > $maxSurcharge) {
                        $maxSurcharge = $surcharge;
                    }
                }
            }
        }

        $this->highestPeakSurcharge = $maxSurcharge;
        $this->peakSinglePrice = $peakSingleRates->min() ?? 0;
        $this->peakDoublePrice = $peakDoubleRates->min() ?? 0;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.overview');
    }
}

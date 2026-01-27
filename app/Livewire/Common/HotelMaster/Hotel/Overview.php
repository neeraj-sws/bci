<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\MarketingCompanies;
use App\Models\PeackDate;
use App\Models\RoomCategory;

class Overview extends Component
{
    public $hotelId;
    public $hotel;
    public $marketingCompany;
    public $parentChain;
    public $lowestSinglePrice = 0;
    public $lowestDoublePrice = 0;
    public $highestPeakSurcharge = 0;
    public $peakSinglePrice = 0;
    public $peakDoublePrice = 0;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->hotel = Hotel::with(['hotelType', 'hotelCategory', 'hotelMealType', 'marketingCompany', 'parentChain','country','state','city'])->find($this->hotelId);

        // Load Marketing Company or Parent Chain using relationships
        $this->marketingCompany = $this->hotel->marketingCompany;
        $this->parentChain = $this->hotel->parentChain;

        // Calculate pricing snapshot
        $this->calculatePricingSnapshot();
    }

    public function calculatePricingSnapshot()
    {
        $roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with('occupancies.occupancy')
            ->get();

        // Get all rates
        $allRates = $roomCategories->flatMap(function($room) {
            return $room->occupancies;
        });

        // Find Single and Double rates
        $singleRates = $allRates->where('occupancy.title', 'Single')->pluck('rate');
        $doubleRates = $allRates->where('occupancy.title', 'Double')->pluck('rate');

        $this->lowestSinglePrice = $singleRates->min() ?? 0;
        $this->lowestDoublePrice = $doubleRates->min() ?? 0;

        // Calculate highest peak surcharge and peak rates
        $peakDates = PeackDate::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with('occupancies.occupancy')
            ->get();

        $maxSurcharge = 0;
        $peakSingleRates = collect();
        $peakDoubleRates = collect();

        foreach ($peakDates as $peakDate) {
            foreach ($peakDate->occupancies as $peakOccupancy) {
                // Collect peak rates by occupancy type
                if ($peakOccupancy->occupancy && $peakOccupancy->occupancy->title === 'Single') {
                    $peakSingleRates->push($peakOccupancy->rate);
                }

                if ($peakOccupancy->occupancy && $peakOccupancy->occupancy->title === 'Double') {
                    $peakDoubleRates->push($peakOccupancy->rate);
                }

                // Find the base rate for this occupancy type
                $baseRate = $allRates->where('occupancy_id', $peakOccupancy->occupancy_id)->pluck('rate')->min();

                if ($baseRate) {
                    // Calculate surcharge (difference between peak rate and base rate)
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
